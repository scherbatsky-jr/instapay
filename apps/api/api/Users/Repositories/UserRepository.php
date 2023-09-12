<?php

namespace Api\Users\Repositories;

use Api\LeadEvents\Models\ProcessEvent;
use Api\Leads\Models\Lead;
use Api\Users\Models\Role;
use Api\Users\Models\User;
use App\AbstractEntityRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository extends AbstractEntityRepository
{
    public function dailyOperatorStats(User $user)
    {
        $from = Carbon::now()->format('Y-m-d 00:00:00');
        $to = Carbon::now()->format('Y-m-d 23:59:59');

        return ProcessEvent::where('user_id', $user->id)
            ->whereBetween('created_at', [$from, $to])
            ->select(DB::raw('COUNT(*) as leads_processed'))
            ->first();
    }

    public function filterRole($query, $method, $clause_operator, $value)
    {
        $role = Role::getRole($value);

        $query
            ->{$method}('user_roles.role_id', $clause_operator, $role->id);
    }

    public function getModelClass(): string
    {
        return User::class;
    }

    public function getOperatorStats($startDate, $endDate)
    {
        $operators = $this->getListQuery()
            ->get()
            ->filter(function ($user) {
                return $user->isOperator();
            });

        $total = [
            'call_failed' => 0,
            'call_later' => 0,
            'pre_qualified' => 0,
            'qualified' => 0,
            'rejected' => 0,
            'leads_processed' => 0,
        ];

        foreach ($operators as $operator) {
            $stats = ProcessEvent::where('user_id', $operator->id)
                ->whereBetween('created_at', [$startDate, $endDate->endofDay()])
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique('lead_id');

            $callFailed = $stats->filter(function ($stat) {
                return !(Lead::STATUS_REJECTED == $stat->status || Lead::STATUS_CALL_LATER == $stat->status)
                    && (Lead::STATUS_NO_ANSWER_1 == $stat->status || Lead::STATUS_NO_ANSWER_2 == $stat->status || Lead::STATUS_NO_ANSWER_3 == $stat->status);
            })->count();
            $callLater = $stats->where('status', Lead::STATUS_CALL_LATER)->count();
            $preQualified = $stats->where('status', Lead::STATUS_PRE_QUALIFIED)->count();
            $qualified = $stats->where('status', Lead::STATUS_QUALIFIED)->count();
            $rejected = $stats->where('status', Lead::STATUS_REJECTED)->count();
            $leadsProcessed = $stats->count();

            $operator['stats'] = [
                'call_failed' => $callFailed,
                'call_later' => $callLater,
                'pre_qualified' => $preQualified,
                'qualified' => $qualified,
                'rejected' => $rejected,
                'leads_processed' => $leadsProcessed,
            ];

            $total['call_failed'] += $callFailed;
            $total['call_later'] += $callLater;
            $total['pre_qualified'] += $preQualified;
            $total['qualified'] += $qualified;
            $total['rejected'] += $rejected;
            $total['leads_processed'] += $leadsProcessed;
        }

        return [
            'users' => $operators->filter(function ($user) {
                return $user->stats['leads_processed'] > 0;
            }),
            'total' => $total,
        ];
    }

    public function getUserByEmail($email)
    {
        return $this->getModelClass()::query()
            ->where('email', $email)
            ->first();
    }

    public function joinRole($query)
    {
        if (!$this->checkQueryHasJoinedTable($query, 'user_roles')) {
            $query->leftJoin('user_roles', 'user_roles.user_id', '=', 'users.id');
        }
    }

    public function resetPassword(User $user, $newPassword)
    {
        $user->password = Hash::make($newPassword);

        $user->save();

        return $user;
    }

    public function updatePassword(User $user, array $data)
    {
        $doesPasswordMatch = Hash::check($data['current_password'], $user->password);

        if ($doesPasswordMatch) {
            $user->password = Hash::make($data['new_password']);

            $user->save();
        } else {
            throw new \Exception('wrong current password');
        }

        return $user;
    }
}
