<?php

namespace App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractEntityService
{
    protected $repository;

    public function create($data, array $fields = [])
    {
        $user = $this->getUser();

        if (false === $this->checkCreatePermission($user, $data)) {
            throw $this->getPermissionDeniedException();
        }

        $modifiedData = $this->beforeEntityCreate($data);

        $entity = $this->getRepository()->create($modifiedData, $fields);

        $this->onEntityCreate($entity, $fields, $data);

        return $entity;
    }

    public function delete($id)
    {
        $user = $this->getUser();

        $entity = $this->getRequestedEntity($id);

        if ($entity && $user) {
            if (false === $this->checkDeletePermission($user, $entity)) {
                throw $this->getPermissionDeniedException();
            }
        }

        try {
            $this->beforeEntityDelete($entity);

            $entity->delete();

            $this->onEntityDelete($entity);

            $result = true;
        } catch (\Throwable $e) {
            $result = false;
        }

        return [
            'success' => $result,
        ];
    }

    public function getAll(array $fields = [], array $args = [])
    {
        $user = $this->getUser();

        if ($user && false === $this->checkGetAllPermission($user)) {
            throw $this->getPermissionDeniedException();
        }

        return $this->getRepository()->get($fields, $args);
    }

    public function getById($id, array $fields = [])
    {
        $user = $this->getUser();

        $entity = $this->getRequestedEntity($id, $fields);

        if ($entity && false === $this->checkGetPermission($user, $entity)) {
            throw $this->getPermissionDeniedException();
        }

        return $entity;
    }

    final public function getUser()
    {
        return $this->getRepository()
            ->getUser();
    }

    public function paginate(array $fields = [], array $args = [])
    {
        $user = $this->getUser();

        if ($user && false === $this->checkGetAllPermission($user)) {
            throw $this->getPermissionDeniedException();
        }

        return $this->getRepository()->getListQuery($fields, $args);
    }

    final public function setUser($user)
    {
        $this->getRepository()
            ->setUser($user);

        return $this;
    }

    public function update($data, array $fields = [])
    {
        $user = $this->getUser();

        $id = data_get($data, 'id');

        $entity = $this->getRequestedEntity($id);

        if ($entity && false === $this->checkUpdatePermission($user, $entity, $data)) {
            throw $this->getPermissionDeniedException();
        }

        $modifiedData = $this->beforeEntityUpdate($entity, $data);

        $entity = $this->getRepository()->update($entity, $modifiedData, $fields);

        $this->onEntityUpdate($entity, $fields, $data);

        return $entity;
    }

    protected function beforeEntityCreate($data)
    {
        return $data;
    }

    protected function beforeEntityDelete($entity)
    {
        return true;
    }

    protected function beforeEntityUpdate($entity, $data)
    {
        return $data;
    }

    protected function checkCreatePermission($user, $data): ?bool
    {
        return null;
    }

    protected function checkDeletePermission($user, $entity): ?bool
    {
        return null;
    }

    protected function checkGetAllPermission($user): ?bool
    {
        return null;
    }

    protected function checkGetPermission($user, $entity): ?bool
    {
        return null;
    }

    protected function checkUpdatePermission($user, $entity, $data): ?bool
    {
        return null;
    }

    protected function getPermissionDeniedException()
    {
        return new HttpException(
            $this->getUser() ? Response::HTTP_FORBIDDEN : Response::HTTP_UNAUTHORIZED,
            'Permission denied.'
        );
    }

    protected function getRepository(): AbstractEntityRepository
    {
        return $this->repository;
    }

    protected function getRequestedEntity($id, array $fields = [])
    {
        $entity = $this->getRepository()->getById($id, $fields);

        return $entity;
    }

    protected function onEntityCreate($entity, array $fields = [], $data = [])
    {
        return;
    }

    protected function onEntityDelete($entity)
    {
        return;
    }

    protected function onEntityUpdate($entity, array $fields = [], $data = [])
    {
        return;
    }
}
