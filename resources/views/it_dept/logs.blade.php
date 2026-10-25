<x-layout>
    <div class="container">
        <div class="page-inner">


            <div class="card logs-table">
                <div class="logs-header d-flex align-items-center">
                    <h4 class="mw-title">Activity Logs</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 680px;" placeholder="Search Logs..." />
                    {{-- <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#manageSharedWindow">
                        <i class="fa fa-plus"></i>
                    </button> --}}
                </div>
                <div class="logs-table-body">
                    <div class="logs-table-content">
                    
                        <table id="basic-datatables" class="display table">
                            <thead>
                                <tr class="logs-column-name">

                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>In</th>
                                        <th>Out</th>
                                        <th>Activity</th>
                                        <th>Date</th>
                                </tr>
                            </thead>
                            <tbody class="logs-column-body">
                                @forelse ($logs as $log)
                                    <tr class="logs-column-name">

                                            <td>{{ $log->name }}</td>
                                            <td>{{ $log->department }}</td>
                                            <td>{{ $log->position }}</td>
                                            <td>{{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}</td>
                                            <td>{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : 'Still logged in' }}</td>
                                            <td>{{ $log->activity }}</td>
                                            <td>{{ \Carbon\Carbon::parse($log->date)->format('Y-m-d') }}</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No logs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-layout>
