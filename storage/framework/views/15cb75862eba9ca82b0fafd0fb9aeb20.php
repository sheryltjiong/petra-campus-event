
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCU Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
</head>
<body class="bg-[#EBEAE9] min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-blue-950 text-white p-4 flex justify-between items-center">
        <div class="flex items-center">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="PCU Logo" class="mr-2 h-6 w-6 object-contain" onerror="this.style.display='none'">
            <span class="font-bold">PCU Events</span>
        </div>
        <div class="space-x-4">
            <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-gray-300">Admin Dashboard</a>
                <?php endif; ?>
                 <a href="<?php echo e(route('home')); ?>">Home</a>
                <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-gray-300">Dashboard</a>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="hover:text-gray-300">Logout</button>
                </form>
            <?php else: ?>
                <?php if(Request::is('login')): ?>
                    <a href="<?php echo e(route('register')); ?>" class="hover:text-gray-300">Sign Up</a>
                    <a href="<?php echo e(route('home')); ?>" class="hover:text-gray-300">Home</a>
                <?php elseif(Request::is('register')): ?>
                    <a href="<?php echo e(route('login')); ?>" class="hover:text-gray-300">Login</a>
                    <a href="<?php echo e(route('home')); ?>" class="hover:text-gray-300">Home</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="hover:text-gray-300">Login</a>
                    <a href="<?php echo e(route('register')); ?>" class="hover:text-gray-300">Sign Up</a>
                    <a href="<?php echo e(route('home')); ?>" class="hover:text-gray-300">Home</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1">
        <?php if(session('success')): ?>
            <div class="max-w-6xl mx-auto px-4 pt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('success')); ?>

                </div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="max-w-6xl mx-auto px-4 pt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('error')); ?>

                </div>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="max-w-6xl mx-auto px-4 pt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p class="text-sm">© Petra Christian University Events 2025</p>
            <div class="mt-4 space-x-6">
                <a href="<?php echo e(route('home')); ?>" class="text-gray-300 hover:text-white text-sm">Home</a>
                <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-300 hover:text-white text-sm">Dashboard</a>
                <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="text-gray-300 hover:text-white text-sm">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</body>
</html><?php /**PATH D:\laragon\www\Petra Campus Event\resources\views/layouts/app.blade.php ENDPATH**/ ?>