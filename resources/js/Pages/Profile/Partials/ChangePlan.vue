<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import RadioInput from '@/Components/RadioInput.vue';

const props = defineProps({
    userPlan: {
        type: Object,
    },
    showSubscriptionForm: {
        type: Boolean,
        default: false,
    },
    plans: {
        type: Object,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    plan: props.userPlan.plan.plan_id,
});

const submit = async () => {
    
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold text-zinc-800">
              <button 
                class="mr-4 text-gray-900"
                @click="$emit('toggle-subscription-form')"
              >
                <font-awesome-icon icon="fa-solid fa-arrow-left"/>
              </button>
              Change Subscription Plan
            </h2>
        </header>

        <div class="mt-6 space-y-6">
          <div class="flex flex-col">
            <h5 class="font-bold text-gray-900 text-md">
              Select your new plan
            </h5>

            <p class="mt-1 text-sm text-gray-600">
                You can cancel new plan any time.
            </p>
          </div>
            
          <div class="flex flex-col gap-3">
            <form @submit.prevent="submit"> 
              <div class="flex flex-row justify-between" v-for="(plan, index) in plans" :key="plan.id">
                <div class="flex">
                  <label class="flex items-center">
                    <input
                      type="radio"
                      :name="plan"
                      :value="plan.id"
                      v-model="form.plan"
                      :checked="parseInt(form.plan) === plan.id"
                      class="w-5 h-5 text-blue-600 form-radio"
                    />
                    <div>
                      <span class="ml-2 text-lg font-bold text-stone-700"> 
                        {{ plan.name?.en }}
                      </span>
                      <br>
                      <span class="ml-2 font-light text-stone-400">
                        {{ plan.charge.prices[0].amount }} {{ plan.charge.prices[0].currency }} / 
                        {{ plan.charge.period?.value }} {{ plan.charge.period?.type }}
                      </span>
                    </div>
                  </label>
                </div>
              </div>
              <div class="flex items-center justify-between gap-4 mt-4">
                <button 
                  class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-teal-600 uppercase transition duration-150 ease-in-out border border-teal-600 rounded-md hover:outline-none hover:bg-teal-600 hover:text-white"
                  type="submit"
                >
                  Choose Plan
                </button>
                <!-- <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition> -->
              </div>
            </form>
        </div>
      </div>
    </section>
</template>
