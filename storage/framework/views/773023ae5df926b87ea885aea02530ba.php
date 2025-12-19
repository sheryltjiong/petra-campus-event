<?php $__env->startSection('content'); ?>
    <div class="relative bg-cover bg-center text-white overflow-hidden" style="background-image: url('/images/banner.png')">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-10 max-w-6xl mx-auto px-4 py-16 sm:py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Don't wait for opportunity, create it!
                </h1>
                <p class="text-xl md:text-2xl mb-4 max-w-4xl mx-auto">
                    Mau ikut acara kampus atau daftar jadi panitia? Di PCU Events, kamu bisa lihat event-event seru,
                    langsung daftar, dan terlibat aktif di kehidupan kampus.
                </p>
                <p class="text-lg md:text-xl mb-8">
                    Nggak cuma jadi penonton – jadi bagian dari pengalaman serunya. Waktunya aktif, waktunya kontribusi.
                    Yuk, mulai dari PCU Events!
                </p>
                <?php if(auth()->guard()->guest()): ?>
                    <div class="space-x-4">
                        <a href="<?php echo e(route('login')); ?>"
                            class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                            Login
                        </a>
                        <a href="<?php echo e(route('register')); ?>"
                            class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition duration-300">
                            Sign Up
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-12 mb-16">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Events</h2>
            <form method="GET" action="<?php echo e(route('events.index')); ?>">
                <div class="relative">
                    <select id="filterSelect"
                        class="appearance-none bg-white border border-gray-300 px-4 py-2 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua</option>
                        <option value="panitia">Dicari: Panitia</option>
                        <option value="peserta">Dicari: Peserta</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                            <path
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                        </svg>
                    </div>
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const filterSelect = document.getElementById("filterSelect");

                        filterSelect.addEventListener("change", function () {
                            const filter = this.value;

                            fetch(`/events?filter=${filter}`, {
                                headers: {
                                    "X-Requested-With": "XMLHttpRequest"
                                }
                            })
                                .then(res => res.json())
                                .then(data => {
                                    document.getElementById("eventsGrid").innerHTML = data.html;
                                })
                                .catch(err => {
                                    console.error("Gagal ambil data event:", err);
                                });
                        });
                    });
                </script>

            </form>

        </div>

        <div id="eventsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 flex flex-col">
                    
                    
                    <div class="relative h-48 bg-gray-200">
                        <?php if($event->image): ?> 
                            
                            <img src="<?php echo e(Storage::url($event->image)); ?>" 
                                alt="<?php echo e($event->name); ?>" 
                                class="w-full h-full object-cover">
                        <?php else: ?>
                            
                            <div class="flex items-center justify-center h-full bg-blue-100 text-blue-500">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        
                        <?php if($event->skkk_category): ?>
                            <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-full shadow">
                                <?php echo e($event->skkk_category); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    

                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-lg text-gray-800 leading-tight pr-2"><?php echo e($event->name); ?></h3>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                <?php echo e(\Carbon\Carbon::parse($event->date)->format('d M Y')); ?>

                            </p>
                            <p class="text-sm text-gray-600 mb-4">
                                <?php if($event->slot_peserta > 0): ?>
                                    <span class="text-green-600 font-medium">Dicari: Peserta</span>
                                <?php else: ?>
                                    <span class="text-blue-600 font-medium">Dicari: Panitia</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <a href="<?php echo e(route('events.show', $event->id)); ?>"
                            class="inline-block w-full text-center bg-blue-950 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition duration-300 mt-2">
                            More Info
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-16">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-12 w-12 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L3 12m0 0l6.75-5M3 12h18" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-700">Tidak ada event ditemukan.</h3>
                </div>
            <?php endif; ?>
        </div>

        <?php if($events->isEmpty()): ?>
            <div class="text-center py-12">
                <div class="text-gray-500 text-lg">
                    <p>Belum ada event tersedia.</p>
                    <p class="text-sm mt-2">Silakan kembali lagi nanti untuk melihat event terbaru!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Petra Campus Event\resources\views/events/index.blade.php ENDPATH**/ ?>