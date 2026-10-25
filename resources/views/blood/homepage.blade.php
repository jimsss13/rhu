<x-layout>
    <div class="manage-window-card">


         <!-- Main content -->
         <h2 class="text-xl font-semibold mb-4">Overview</h2>
         <div class="grid grid-cols-4 gap-4 mb-6">
             <div class="bg-blue-100 p-4 rounded-lg flex items-center min-h-[150px]">
                 <i class="fas fa-users text-blue-600 text-2xl mr-3"></i>
                 <div class="text-center flex-1">
                     <span class="text-2xl font-bold">{{ $totalCount ?? 'N/A' }}</span>
                     <p class="text-zinc-600">Total Patient</p>
                 </div>
             </div>
             <div class="bg-yellow-100 p-4 rounded-lg flex items-center min-h-[150px]">
                 <i class="fas fa-clock text-yellow-600 text-2xl mr-3"></i>
                 <div class="text-center flex-1">
                     <span class="text-2xl font-bold">{{ $pendingCount ?? 'N/A' }}</span>
                     <p class="text-zinc-600">Total Pending</p>
                 </div>
             </div>
             <div class="bg-green-100 p-4 rounded-lg flex items-center min-h-[150px]">
                 <i class="fas fa-check text-green-600 text-2xl mr-3"></i>
                 <div class="text-center flex-1">
                     <span class="text-2xl font-bold">{{ $approvedCount ?? 'N/A' }}</span>
                     <p class="text-zinc-600">Total Approved</p>
                 </div>
             </div>
             <div style="height: 15rem" class="bg-zinc-100 p-4 rounded-lg flex items-center min-h-[150px]">
                 <i class="fas fa-tasks text-zinc-600 text-2xl mr-3"></i>
                 <div class="text-center flex-1">
                     <span class="text-2xl font-bold">{{ $completedCount ?? 'N/A' }}</span>
                     <p class="text-zinc-600">Total Completed</p>
                 </div>
             </div>
         </div>


        <h2 class="text-xl font-semibold mb-4">Blood Donation</h2>

        <div class="row row-cols-1 row-cols-md-4 g-4 mb-6">
            @foreach ($bloodTypes as $bloodType)
            <div class="col">
                <div class="card shadow-sm h-100 border-light rounded-lg hover:shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                    <div class="card-body d-flex flex-column align-items-center bg-gradient p-4">
                        <!-- Blood Type Icon -->
                        <i class="fas fa-blood-drop text-primary mb-3" style="font-size: 40px;"></i>
                        <h5 class="card-title text-center text-dark font-weight-bold">{{ $bloodType->blood_type }}</h5>

                        <!-- Blood Type Details -->
                        <ul class="list-unstyled text-center">
                            <li class="mb-2">
                                <strong>Total:</strong> <span class="font-weight-bold text-info">{{ $bloodType->total }} ml</span>
                            </li>
                            <li class="mb-2">
                                <strong>Current:</strong> <span class="font-weight-bold text-warning">{{ $bloodType->current }} ml</span>
                            </li>
                            <li>
                                <strong>Turned Over:</strong> <span class="font-weight-bold text-success">{{ $bloodType->turned_over }} ml</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-layout>
