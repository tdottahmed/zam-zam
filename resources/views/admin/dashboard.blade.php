@extends('layouts.admin')

@section('header')
    Dashboard
@endsection

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Users -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
             <div class="absolute right-0 top-0 w-24 h-24 bg-[#C41E3A]/5 rounded-bl-[100px] -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
             
             <div class="flex flex-col relative z-10">
                 <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Users</p>
                 <div class="flex items-baseline space-x-2 mt-2">
                     <h3 class="text-3xl font-bold text-gray-900">{{ \App\Models\User::where('user_type', 'user')->count() }}</h3>
                     <span class="text-sm font-medium text-green-500 bg-green-50 px-2 py-0.5 rounded-full">+12%</span>
                 </div>
             </div>
             <div class="mt-4">
                 <div class="w-full bg-gray-100 rounded-full h-1.5">
                     <div class="bg-[#C41E3A] h-1.5 rounded-full" style="width: 70%"></div>
                 </div>
             </div>
        </div>

        <!-- Total Orders -->
         <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
             <div class="absolute right-0 top-0 w-24 h-24 bg-blue-500/5 rounded-bl-[100px] -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
             
             <div class="flex flex-col relative z-10">
                 <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Orders</p>
                 <div class="flex items-baseline space-x-2 mt-2">
                     <h3 class="text-3xl font-bold text-gray-900">1,245</h3>
                     <span class="text-sm font-medium text-green-500 bg-green-50 px-2 py-0.5 rounded-full">+5%</span>
                 </div>
             </div>
              <div class="mt-4">
                 <div class="w-full bg-gray-100 rounded-full h-1.5">
                     <div class="bg-blue-500 h-1.5 rounded-full" style="width: 45%"></div>
                 </div>
             </div>
        </div>

        <!-- Revenue -->
         <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
             <div class="absolute right-0 top-0 w-24 h-24 bg-green-500/5 rounded-bl-[100px] -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
             
             <div class="flex flex-col relative z-10">
                 <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Revenue</p>
                 <div class="flex items-baseline space-x-2 mt-2">
                     <h3 class="text-3xl font-bold text-gray-900">$45.2k</h3>
                     <span class="text-sm font-medium text-green-500 bg-green-50 px-2 py-0.5 rounded-full">+18%</span>
                 </div>
             </div>
             <div class="mt-4">
                 <div class="w-full bg-gray-100 rounded-full h-1.5">
                     <div class="bg-green-500 h-1.5 rounded-full" style="width: 80%"></div>
                 </div>
             </div>
        </div>

         <!-- System Status -->
         <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
             <div class="absolute right-0 top-0 w-24 h-24 bg-orange-500/5 rounded-bl-[100px] -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
             
             <div class="flex flex-col relative z-10">
                 <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">System Status</p>
                 <div class="flex items-center space-x-2 mt-2 h-9">
                     <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <span class="text-lg font-bold text-green-600">Operational</span>
                 </div>
             </div>
             <div class="mt-4 text-xs text-gray-400">
                Last checked: 2 mins ago
             </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Column (Tables etc) -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800">Recent Users</h3>
                    <button class="text-sm text-[#C41E3A] font-medium hover:underline">View All</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-medium">User</th>
                                <th class="px-6 py-4 font-medium">Role</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 font-medium">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->user_type === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($user->user_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Widgets Column -->
        <div class="space-y-6">
            <div class="bg-[#1A1A1A] text-white rounded-2xl p-6 shadow-lg relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-bold text-xl mb-2">Upgrade Plan</h3>
                    <p class="text-gray-400 text-sm mb-6">Unlock partial features and more.</p>
                    <button class="w-full py-2 bg-[#C41E3A] hover:bg-[#a91930] rounded-lg font-bold transition shadow-lg shadow-red-900/20">
                        Upgrade Now
                    </button>
                </div>
                 <!-- Decorative -->
                 <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                 <h3 class="font-bold text-gray-800 mb-4">Quick Actions</h3>
                 <div class="space-y-3">
                     <button class="w-full flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition group">
                         <span class="text-sm font-medium text-gray-700 group-hover:text-[#C41E3A]">Add New Product</span>
                         <span class="text-gray-400 group-hover:translate-x-1 transition">+</span>
                     </button>
                     <button class="w-full flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition group">
                         <span class="text-sm font-medium text-gray-700 group-hover:text-[#C41E3A]">Create Order</span>
                         <span class="text-gray-400 group-hover:translate-x-1 transition">+</span>
                     </button>
                     <button class="w-full flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition group">
                         <span class="text-sm font-medium text-gray-700 group-hover:text-[#C41E3A]">Manage Users</span>
                         <span class="text-gray-400 group-hover:translate-x-1 transition">→</span>
                     </button>
                 </div>
            </div>
        </div>
    </div>
@endsection
