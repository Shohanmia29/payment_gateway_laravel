<x-admin-app-layout>
    <div class="w-full flex justify-between">
        <div class="text-xl">{{ __('Users') }}</div>
        {{--@can('user-create')
            <div>
                <a
                    href="{{ route('admin.user.create') }}"
                    class="bg-transparent hover:bg-blue-500 text-blue-700 text-sm font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
                >
                    + {{ __('Create User') }}
                </a>
            </div>
        @endcan--}}
    </div>

    <form action="{{ route('admin.user.index') }}">
        <div class="w-full mt-8 flex justify-between items-center flex-wrap">
            <div class="w-full lg:w-1/3 flex p-4">
                <x-label class="flex-grow">{{ __('Start Date') }}</x-label>
                <x-input class="w-auto ml-4 flex-grow" type="date" name="start_date" :value="request('start_date')" required/>
            </div>
            <div class="w-full lg:w-1/3 flex p-4">
                <x-label class="flex-grow">{{ __('End Date') }}</x-label>
                <x-input class="w-auto ml-4 flex-grow" type="date" name="end_date" :value="request('end_date')" required/>
            </div>
            <div class="w-full flex justify-end lg:w-1/3 flex-grow p-4">
                <x-button>{{ __('Filter') }}</x-button>
                <a class="ml-4" href="{{ route('admin.user.index') }}"><x-button type="button">{{ __('Reset') }}</x-button></a>
            </div>
        </div>
    </form>


    <div class="w-full mt-8">
        <table class="w-full" id="users-table">
            <thead>
            <tr>
                <th>{{ __('SL') }}</th>
                <th>{{ __('Registered At') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
            </thead>
        </table>
    </div>
    <x-slot name="script">
        <script type="text/javascript" src="{{ mix('js/datatable.js') }}"></script>
        <script type="text/javascript">
            $('#users-table').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{{ route('admin.user.index') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}',
                    dataSrc(response) {
                        response.data.map(function (item) {
                            item.referrer = item.referrer ?? {phone: ''};
                            let actionConfig = {
                                'show': '{{ route('admin.user.show', '@') }}'.replace('@', item.id),
                                @can('user-update')
                                'portal': '{{ route('admin.user.portal', '@') }}'.replace('@', item.id),
                                'edit': '{{ route('admin.user.edit', '@') }}'.replace('@', item.id),
                                    @endcan
                                    @can('user-delete')
                                'delete': '{{ route('admin.user.destroy', '@') }}'.replace('@', item.id),
                                @endcan
                            }
                            item.created_at = (new Date(item.created_at)).toLocaleDateString()
                            item.status = @js(\App\Enums\UserStatus::asSelectArray())[item.status]
                            item.action = actionIcons(actionConfig);
                            item.referrer = item.referrer ?? { phone: '' };
                            return item;
                        });
                        return response.data;
                    }
                },
                dom:
                    "<'flex flex-wrap'<'w-full flex justify-center my-1 sm:justify-end sm:w-1/2'f>>" +
                    "<'flex my-4'<'w-full overflow-y-auto'tr>>" +
                    "<'flex flex-wrap'<'w-full my-2 sm:w-1/3'i><'w-full sm:w-2/3 text-right'p>>",
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'created_at'},
                    {data: 'phone'},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'status'},
                    {data: 'action', orderable: false, searchable: false},
                ]
            });
        </script>
    </x-slot>
</x-admin-app-layout>
