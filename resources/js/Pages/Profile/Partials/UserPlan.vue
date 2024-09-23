<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    userPlan: {
        type: Object,
    },
    showSubscriptionForm: {
        type: Boolean,
        default: false,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    user_id: user.id,
    plan_id: props.userPlan.plan.plan_id,
});

// const showSubscriptionForm = ref(false);

const page = usePage();
const flash = page.props.flash;

if (flash.success) {
  Swal.fire({
    title: "Success!",
    text: flash.success,
    icon: "success"
  });
} else if (flash.error) {
  Swal.fire({
    title: "Error",
    text: flash.error,
    icon: "error"
  });
}

const submitCancel = async () => {
    await Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to cancel the plan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel plan'
    }).then((result) => {
        if (result.isConfirmed) {
          cancelPlan();
        }
    });
};

const cancelPlan = async () => {
  // form.post(route('profile.cancelPlan'));

  form.post(route('profile.cancelPlan'), {
    onSuccess: (data) => {
        Swal.fire({
            title: "Success!",
            text: data.success || "Subscription has been canceled.",
            icon: "success"
        })
        // .then(() => {
        //     location.reload();
        // });
    },
    onError: (error) => {
        Swal.fire({
            title: "Error",
            text: error.response.data.error || "An error occurred. Please try again.",
            icon: "error"
        });
    }
  });
};

const submitStopRenewal = async () => {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to stop renew the plan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, stop renew the plan'
    });

    if (result.isConfirmed) {
        await nonRenewPlan();
    }
};

const nonRenewPlan = async () => {
    try {
        const response = await form.post(route('profile.nonRenewPlan'));
        const data = response.data;

        Swal.fire({
            title: "Success!",
            text: data.success || "Subscription will not be renewed.",
            icon: "success"
        });
      
        // location.reload();
    } catch (error) {
        Swal.fire({
            title: "Error",
            text: error.response.data.error || "An error occurred. Please try again.",
            icon: "error"
        });
    }
};


// const nonRenewPlan = async () => {
//   form.post(route('profile.nonRenewPlan'), {
//     onSuccess: (data) => {
//         Swal.fire({
//             title: "Success!",
//             text: data.success || "Subscription will not be renewed.",
//             icon: "success"
//         })
//         // .then(() => {
//         //     location.reload();
//         // });
//     },
//     onError: (error) => {
//         Swal.fire({
//             title: "Error",
//             text: error.response.data.error || "An error occurred. Please try again.",
//             icon: "error"
//         });
//     }
//   });
// };
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold text-zinc-800">Your Subscription Plan</h2>
        </header>

        <div class="mt-6 space-y-6">
          <div class="flex flex-col gap-3">
            <div class="flex flex-row justify-between">
              <div class="font-medium text-stone-400">Subscription</div>
              <div>
                <div class="flex items-center ms-4" v-if="userPlan.plan.name === 'Basic'">
                    <span class="px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-amber-400 text-amber-700">
                        {{ userPlan.plan.name }}
                    </span>
                </div>

                <div class="flex items-center ms-4" v-if="userPlan.plan.name === 'Premium'">
                    <span class="px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-rose-300 text-rose-700">
                        {{ userPlan.plan.name }}
                    </span>
                </div>
                
                <div class="flex items-center ms-4" v-if="userPlan.plan.name === 'Pro'">
                    <span class="px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-sky-300 text-sky-700">
                        {{ userPlan.plan.name }}
                    </span>
                </div>
              </div>
            </div>
            
            <div class="flex flex-row justify-between">
              <div class="font-medium text-stone-400">Price</div>
              <div>RM {{ userPlan.plan.price }}</div>
            </div>

            <div class="flex flex-row justify-between">
              <div class="font-medium text-stone-400">Next billing date</div>
              <div>{{ userPlan.end_date_formatted }}</div>
            </div>
          </div>

          <div class="flex items-center justify-between gap-4">
            <button 
              class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-pink-600 uppercase transition duration-150 ease-in-out border border-pink-600 rounded-md hover:outline-none hover:bg-pink-600 hover:text-white"
              @click="$emit('toggle-subscription-form')"
              v-if="!showSubscriptionForm"
            >
              Change Subscription Plan
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

            <form @submit.prevent="submitCancel" v-if="userPlan.status == 'non_renewing'">
              <input type="hidden" name="user_id" :value="user.id" />
              <button 
                  type="submit"
                  class="inline-flex items-center py-2 text-xs font-semibold uppercase text-slate-500"
                  v-if="!showSubscriptionForm"
              >
                  Cancel Subscription
              </button>
            </form>

            <form @submit.prevent="submitStopRenewal" v-if="userPlan.status == 'active'">
              <input type="hidden" name="user_id" :value="user.id" />
              <button 
                  type="submit"
                  class="inline-flex items-center py-2 text-xs font-semibold uppercase text-slate-500"
                  v-if="!showSubscriptionForm"
              >
                  Stop Renewing
              </button>
            </form>
          </div>
        </div>
    </section>
</template>
