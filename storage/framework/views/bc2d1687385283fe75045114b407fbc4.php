<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-8 px-4 max-w-4xl">
    <div class="mb-6">
        <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-blue-950 text-white p-6">
            <h1 class="text-3xl font-bold mb-2"><?php echo e($event->name); ?></h1>
            <div class="flex flex-wrap gap-4 text-sm opacity-90">
                <span>📅 <?php echo e(\Carbon\Carbon::parse($event->date)->format('l, d M Y')); ?></span>
                <span>🕐 <?php echo e(\Carbon\Carbon::parse($event->time ?? '00:00')->format('H:i')); ?> WIB</span>
                <span>📍 <?php echo e($event->location ?? 'Lokasi TBA'); ?></span>
            </div>
        </div>

        <div class="p-6">
    
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                
                <div class="lg:col-span-1">
                    <div class="rounded-lg overflow-hidden shadow-md border border-gray-200">
                        <?php if($event->image): ?>
                            
                            <img src="<?php echo e(Storage::url($event->image)); ?>" 
                                alt="<?php echo e($event->name); ?>"  
                                class="w-full h-auto object-cover">
                        <?php else: ?>
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-400">
                                <span class="text-sm">No Poster Available</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="lg:col-span-2">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Detail Event</h3>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Penyelenggara:</span> <?php echo e($event->organizer_type ?? 'PCU'); ?></div>
                                <?php if($event->skkk_points || $event->skkkCategoryName): ?>
                                    <div>
                                        <span class="font-medium">SKKK Points (Peserta):</span> 
                                        <?php echo e($event->skkk_points ?? 'Tidak tersedia'); ?>

                                    </div>
                                    <?php if($event->skkkCategoryName): ?>
                                        <div>
                                            <span class="font-medium">Kategori SKKK:</span> 
                                            <?php echo e($event->skkkCategoryName); ?>

                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Status Pendaftaran</h3>
                            <div class="space-y-2">
                                <?php if($registration_phase === 'volunteer_open'): ?>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                        <span class="text-sm">Pendaftaran Panitia: <span class="font-medium text-green-600">Terbuka</span></span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                                        <span class="text-sm">Pendaftaran Peserta: <span class="font-medium text-gray-600">Belum Dibuka</span></span>
                                    </div>
                                <?php elseif($registration_phase === 'participant_open'): ?>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                                        <span class="text-sm">Pendaftaran Panitia: <span class="font-medium text-gray-600">Ditutup</span></span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                        <span class="text-sm">Pendaftaran Peserta: <span class="font-medium text-green-600">Terbuka</span></span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                        <span class="text-sm">Pendaftaran: <span class="font-medium text-red-600">Ditutup</span></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="font-semibold text-gray-800 mb-3">Deskripsi</h3>
                        <div class="prose max-w-none text-gray-700">
                            <?php echo nl2br(e($event->description)); ?>

                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <?php if(auth()->guard()->check()): ?>
                            <?php if($registration_phase === 'volunteer_open'): ?>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="<?php echo e(route('volunteer.register', $event->id)); ?>" class="flex-1 bg-blue-950 text-white text-center px-6 py-3 rounded-lg hover:bg-blue-800 transition font-medium">Join as panitia</a>
                                    <button disabled class="flex-1 bg-gray-300 text-gray-500 text-center px-6 py-3 rounded-lg cursor-not-allowed font-medium">Join as peserta (Belum Dibuka)</button>
                                </div>
                            <?php elseif($registration_phase === 'participant_open'): ?>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <button disabled class="flex-1 bg-gray-300 text-gray-500 text-center px-6 py-3 rounded-lg cursor-not-allowed font-medium">Join as panitia (Tutup)</button>
                                    <a href="<?php echo e(route('events.participant-form', $event->id)); ?>" class="flex-1 bg-green-600 text-white text-center px-6 py-3 rounded-lg hover:bg-green-500 transition font-medium">Join as peserta</a>
                                </div>
                            <?php else: ?>
                                <div class="text-center bg-gray-100 p-4 rounded-lg text-gray-600">
                                    Pendaftaran untuk event ini sudah ditutup.
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center">
                                <p class="text-gray-600 mb-4">Silakan login untuk mendaftar ke event ini</p>
                                <a href="<?php echo e(route('login')); ?>" class="inline-block bg-blue-950 text-white px-6 py-2 rounded-lg hover:bg-blue-800">Login</a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div> 
            </div> 
        </div>

    <?php if($event->additional_info ?? false): ?>
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-blue-900 mb-2">More Info</h3>
            <p class="text-blue-800 text-sm"><?php echo e($event->additional_info); ?></p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Petra Campus Event\resources\views/events/show.blade.php ENDPATH**/ ?>