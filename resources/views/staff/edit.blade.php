@extends('layouts.app')

@section('title', 'Edit Staff Member')

@section('actions')
<a href="{{ route('staff.index') }}"
 class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 transition-all hover:bg-slate-50 hover:text-slate-900">
 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
 class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1">
 <path fill-rule="evenodd"
 d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z"
 clip-rule="evenodd" />
 </svg>
 Back to Directory
</a>
@endsection

@section('content')
<div class="mx-auto max-w-3xl">
 <form action="{{ route('staff.update', $staff) }}" method="POST"
 class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]">
 @csrf
 @method('PUT')

 {{-- Header Section --}}
 <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
 <div class="flex items-center gap-4">
 <div
 class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-inset ring-blue-500/20">
 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
 stroke="currentColor" class="h-6 w-6">
 <path stroke-linecap="round" stroke-linejoin="round"
 d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
 </svg>
 </div>
 <div>
 <h2 class="text-lg font-bold text-slate-900">Edit Staff Member</h2>
 <p class="text-sm text-slate-500 mt-0.5">Update details for {{ $staff->display_name }}.</p>
 </div>
 </div>
 </div>

 {{-- Main Form Body --}}
 <div class="px-8 py-8 space-y-8">

 {{-- SECTION: Personal Information --}}
 <div>
 <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
 <path
 d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
 </svg>
 Personal Information
 </h3>

 <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
 <div class="sm:col-span-1">
 <label for="title" class="mb-2 block text-sm font-bold text-slate-700">Title <span
 class="text-slate-400 font-normal ml-1">(Opt)</span></label>
 <input type="text" id="title" name="title" value="{{ old('title', $staff->title) }}"
 placeholder="Dr. / RN"
 class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
 @error('title') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
 </div>

 <div class="sm:col-span-2">
 <label for="name" class="mb-2 block text-sm font-bold text-slate-700">Full Name <span
 class="text-rose-500">*</span></label>
 <input type="text" id="name" name="name" value="{{ old('name', $staff->name) }}" required
 placeholder="e.g. Maria Santos"
 class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
 @error('name') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
 </div>
 </div>
 </div>

 <hr class="border-slate-100">

 {{-- SECTION: Role Details --}}
 <div>
 <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
 <path fill-rule="evenodd"
 d="M10 2a.75.75 0 01.75.75v5.59l1.95-2.1a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0L6.2 7.26a.75.75 0 111.1-1.02l1.95 2.1V2.75A.75.75 0 0110 2z"
 clip-rule="evenodd" />
 <path fill-rule="evenodd"
 d="M4 10a.75.75 0 01.75.75v4.5a.75.75 0 00.75.75h9a.75.75 0 00.75-.75v-4.5a.75.75 0 011.5 0v4.5a2.25 2.25 0 01-2.25 2.25h-9A2.25 2.25 0 012 15.25v-4.5A.75.75 0 014 10z"
 clip-rule="evenodd" />
 </svg>
 Role Details
 </h3>

 <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
 <div>
 <label for="type" class="mb-2 block text-sm font-bold text-slate-700">Type <span
 class="text-rose-500">*</span></label>
 <select id="type" name="type" required
 class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
 <option value="doctor" {{ old('type', $staff->type) === 'doctor' ? 'selected' : '' }}>Doctor
 </option>
 <option value="nurse" {{ old('type', $staff->type) === 'nurse' ? 'selected' : '' }}>Nurse
 </option>
 <option value="technician" {{ old('type', $staff->type) === 'technician' ? 'selected' : ''
 }}>Technician</option>
 <option value="other" {{ old('type', $staff->type) === 'other' ? 'selected' : '' }}>Other
 </option>
 </select>
 @error('type') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
 </div>

 <div>
 <label for="specialization"
 class="mb-2 block text-sm font-bold text-slate-700">Specialization</label>
 <input type="text" id="specialization" name="specialization"
 value="{{ old('specialization', $staff->specialization) }}" placeholder="e.g. Cardiology"
 class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
 @error('specialization') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
 </div>
 </div>
 </div>

 </div>

 {{-- Footer / Submit Area --}}
 <div class="bg-slate-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-100">
 <a href="{{ route('staff.index') }}"
 class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900">
 Cancel
 </a>
 <button type="submit"
 class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
 <span class="relative">Save Changes</span>
 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
 class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5">
 <path fill-rule="evenodd"
 d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
 clip-rule="evenodd" />
 </svg>
 </button>
 </div>
 </form>
</div>
@endsection