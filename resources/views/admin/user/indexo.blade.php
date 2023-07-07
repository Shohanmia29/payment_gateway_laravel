<x-admin-app-layout>
    <x-slot name="header">
        Users
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a
                href="{{route('admin.user.create')}}"
                class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
            >
                + New User
            </a>
            <div
                class="mt-8 w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full" id="user-table">
                    <thead>
                    <tr>
                        <th class="p-3 text-left text-sm bg-slate-200 text-slate-800 uppercase">Id</th>
                        <th class="p-3 text-left text-sm bg-slate-200 text-slate-800 uppercase">Display Name</th>
                        <th class="p-3 text-left text-sm bg-slate-200 text-slate-800 uppercase">Name</th>
                        <th class="p-3 text-left text-sm bg-slate-200 text-slate-800 uppercase">Permissions</th>
                        <th class="p-3 text-left text-sm bg-slate-200 text-slate-800 uppercase"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white text-sm">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <x-slot name="script">
        <script type="text/javascript" src="{{ mix('js/datatable.js') }}"></script>
        <script type="text/javascript">
            $('#user-table').da
        </script>
    </x-slot>
</x-admin-app-layout>
