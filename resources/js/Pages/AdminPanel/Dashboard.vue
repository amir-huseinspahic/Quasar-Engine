<script setup>
    import { ref } from 'vue';
    import { Link, usePage } from '@inertiajs/vue3';
    import { trans } from 'laravel-vue-i18n';
    import { toArray } from 'lodash';

    import { getHumanReadableTime } from '@/Composables/GetHumanReadableTime.js';


    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import BarChart from '@/Components/Base/BarChart.vue';
    import { ArrowTrendingUpIcon } from '@heroicons/vue/24/outline/index.js';

    const { dayjs, getTimeFromNow } = getHumanReadableTime();

    const props = defineProps({
        laravelVersion: {
            type: String,
            required: true
        },
        phpVersion: {
            type: String,
            required: true
        },
        greeting: {
            type: String,
            default: 'Welcome'
        },
        postData: {
            type: [Object, Array],
            required: true
        },
        lastActiveAt: {
            type: String,
            required: true
        },
        lastLoginAt: {
            type: String,
            required: true
        }
    });

    const postChartLabels = ref([
        trans('January'), trans('February'), trans('March'),
        trans('April'), trans('May'), trans('June'),
        trans('July'), trans('August'), trans('September'),
        trans('October'), trans('November'), trans('December')
    ]);

    let postChartData = ref({
        labels: postChartLabels.value,
        datasets: [{
            label: trans('Posts published this year by month'),
            data: toArray(props.postData),
            backgroundColor: [
                'rgba(153, 102, 255, 0.2)',
            ],
            borderColor: [
                'rgb(153, 102, 255)',
            ],
            borderWidth: 1
        }]
    });

    const postChartOptions = ref({ responsive: true })

    function getFirstName() {
       let name = usePage().props.auth.user.name;

       if (name.split(' ').length > 1) {
           return name.split(' ').slice(0, -1).join(' ');
       }
       else return name;
    }

</script>

<template>
    <AuthenticatedLayout :page-title="$t('Dashboard')">

        <div class="flex flex-col-reverse md:flex-row h-[calc(100vh-64px)]">
            <div>
                <div class="px-6 py-3 bg-white m-4 shadow-lg text-gray-700 justify-center flex space-x-2 flex-wrap text-sm">
                    <p class="font-semibold">{{ $t('Laravel version:') }} </p>
                    <p>{{ props.laravelVersion }}</p>
                </div>

                <div class="px-6 py-3 bg-white m-4 shadow-lg text-gray-700 justify-center flex space-x-2 flex-wrap text-sm">
                    <p class="font-semibold">{{ $t('PHP version:') }} </p>
                    <p>{{ props.phpVersion }}</p>
                </div>
            </div>

            <div class="flex flex-col flex-1">
                <div class="flex p-6 bg-white m-4 shadow-lg text-gray-700 items-center justify-center">
                    <h1 class="text-2xl">{{ greeting + ', ' }} <span class="font-semibold">{{ getFirstName() }}.</span></h1>
                </div>

                <div class="px-6 py-3 bg-white m-4 shadow-lg text-gray-700 relative">
                    <BarChart :chart-data="postChartData" :chart-options="postChartOptions" :maintain-aspect-ratio="false" />
                </div>
            </div>


            <div>
                <div class="px-6 py-3 bg-white m-4 shadow-lg text-gray-700 justify-center flex space-x-2 text-sm flex-wrap">
                    <p class="font-semibold">{{ $t('Last login at:') }}</p>
                    <span v-if="lastLoginAt === null">{{ $t('Never') }}</span>
                    <span v-else>{{ getTimeFromNow(lastLoginAt, $page.props.auth.user.settings.locale, $page.props.auth.user.settings.timezone) }}</span>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
