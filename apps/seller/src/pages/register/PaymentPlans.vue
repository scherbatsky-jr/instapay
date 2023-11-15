<template>
    <div class="d-flex w-100 flex-column m-5">
        <h1 class="text-center">Choose Your Plan</h1>
        <div class="plans">
            <div class="card" :class="getPlanClass(1)" @click="plan = 1">
                <div class="header">Monthly Plan</div>
                <div class="price">Rs 1200/month</div>
                <ul>
                    <li>Billed Rs 1200 Monthly with VAT </li>
                    <li>Unlimited access to all features</li>
                </ul>
            </div>
            <div class="card" :class="getPlanClass(2)" @click="plan = 2">
                <div class="header">Six Months Plan</div>
                <div class="price">Rs 1000/month</div>
                <ul>
                    <li>Billed Rs 6000 bi-yearly with VAT </li>
                    <li>Unlimited access to all features</li>
                </ul>
            </div>
            <div class="card" :class="getPlanClass(3)" @click="plan = 3">
                <div class="header">Yearly Plan</div>
                <div class="price">Rs 800/month</div>
                <ul>
                    <li>Billed Rs 9600 Yearly with VAT </li>
                    <li>Unlimited access to all features</li>
                </ul>
            </div>

            <div class="mt-4">
                <h3>Plan Details:</h3>
                <h4>Your Chosen Plan: {{ chosenPlan.plan }}</h4>
                <h4>Plan Cost: {{ chosenPlan.cost }}</h4>
                <h4>VAT: {{  chosenPlan.vat }}</h4>
                <h4>Total Cost: {{ chosenPlan.totalCost }}</h4>
                <button class="mt-2" @click="onPayEsewa">Pay with Esewa</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PaymentPlans',

    data () {
        return {
            plan: 1
        }
    },

    computed: {
        chosenPlan () {
            const plan = {}

            if (this.plan == 1) {
                plan.plan = 'Monthly Plan'
                plan.cost = 1200
                plan.vat = 1200*0.13
                plan.totalCost = 1200 + 1200*0.13
            } else if (this.plan == 2) {
                plan.plan = 'Six Months Plan'
                plan.cost = 6000
                plan.vat = 6000*0.13
                plan.totalCost = 6000 + 6000*0.13
            } else if (this.plan == 3) {
                plan.plan = 'Yearly Plan'
                plan.cost = 8001
                plan.vat = 9600*0.13
                plan.totalCost = 9600+ 9600*0.13
            }

            return plan
        }
    },

    methods: {
        getPlanClass(plan) {
            if (this.plan === plan) {
                return 'card--selected'
            }

            return ''
        },

        generatePID() {
            return `6545${new Date().toTimeString()}`
        },

        onPayEsewa () {
            var path="https://uat.esewa.com.np/epay/main";
            var params= {
                amt: this.chosenPlan.cost,
                psc: 0,
                pdc: 0,
                txAmt: this.chosenPlan.vat,
                tAmt: this.chosenPlan.totalCost,
                pid: this.generatePID(),
                scd: "EPAYTEST",
                su: `http://localhost:20181/plan-success/${this.plan}`,
                fu: `http://localhost:20181/payment-plans`
            }
            var form = document.createElement("form");
            form.setAttribute("method", "POST");
            form.setAttribute("action", path);

            for(var key in params) {
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                hiddenField.setAttribute("value", params[key]);
                form.appendChild(hiddenField);
            }

            document.body.appendChild(form);
            form.submit();
        },
    }
}
</script>

<style lang="scss">
.plans {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    margin: 3rem auto;
    grid-gap: 1rem;

    .card {
        cursor: pointer;
        text-align: center;

        .header {
            font-size: 2rem
        }

        .price {
            font-size: 3rem;
            margin: 2rem 0;
        }

        &--selected {
            border: 5px solid green;
        }
    }
}
</style>
