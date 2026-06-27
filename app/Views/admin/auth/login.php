<div class="bg-slate-800 rounded-2xl shadow-xl p-8 border border-slate-700">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-white">Ozerman CMS</h1>
        <p class="text-slate-400 text-sm mt-1">Sign in to manage content</p>
    </div>

    <form method="POST" action="/admin/login" class="space-y-5">
        <?= csrf_field() ?>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email</label>
            <input type="email" id="email" name="email" required autofocus
                   class="w-full rounded-lg bg-slate-700 border border-slate-600 text-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="admin@ozermanltd.com">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-1">Password</label>
            <input type="password" id="password" name="password" required
                   class="w-full rounded-lg bg-slate-700 border border-slate-600 text-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors">
            Sign in
        </button>
    </form>

    <p class="text-center text-xs text-slate-500 mt-6">
        Default: admin@ozermanltd.com / admin123<br>
        Run <code class="text-slate-400">make reset-admin-password</code> if login fails
    </p>
</div>
