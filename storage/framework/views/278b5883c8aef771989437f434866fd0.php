<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-cover bg-center px-4 py-8"
     style="background-image: url('<?php echo e(asset('images/background.jpg')); ?>')">

    <div class="bg-white bg-opacity-5 backdrop-blur-md rounded-2xl shadow-2xl p-8 w-full max-w-4xl flex flex-col md:flex-row items-center gap-10 relative text-white">

        <!-- Left Side - Welcome -->
        <div class="w-full md:w-1/2 text-left">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="PCU Logo" class="h-10 w-10 mb-4"
                 onerror="this.style.display='none'">
            <h2 class="text-5xl font-extrabold mb-2">Welcome!</h2>
            <p class="text-lg text-gray-300">Don’t wait for opportunity, create it!</p>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full md:w-1/2 bg-white bg-opacity-10 rounded-xl p-6 shadow-inner">
            <h3 class="text-2xl font-bold mb-6 text-white text-center">Login</h3>

            <?php if($errors->any()): ?>
                <div class="bg-red-100 text-red-700 text-sm p-3 rounded mb-4">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>

                <input type="email" name="email" id="email" required
                       value="<?php echo e(old('email')); ?>"
                       placeholder="user@john.petra.ac.id"
                       class="w-full px-4 py-3 rounded bg-white bg-opacity-90 text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />

                <input type="password" name="password" id="password" required
                       placeholder="Password"
                       class="w-full px-4 py-3 rounded bg-white bg-opacity-90 text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />

                <div class="flex items-center justify-between text-sm text-gray-200">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="remember" class="accent-blue-500" />
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="text-blue-300 hover:underline">Forgot Password?</a>
                </div>

                <button type="submit"
                        class="w-full bg-white text-gray-900 font-semibold px-4 py-3 rounded hover:bg-gray-200 transition">
                    Login
                </button>
            </form>

            <p class="text-sm text-gray-300 mt-6 text-center">
                Don't have an account?
                <a href="<?php echo e(route('register')); ?>" class="text-blue-300 underline">Sign Up</a>
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Petra Campus Event\resources\views/auth/login.blade.php ENDPATH**/ ?>