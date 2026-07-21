@extends('layouts.admin')
@section('title','Kelola User — Admin')
@section('breadcrumb','Kelola User')

@section('content')
<div class="section-header">
    <h4>Kelola User</h4>
    <p>Tambah, hapus, dan kelola semua akun user pada sistem.</p>
</div>

<div class="row g-3">
    {{-- Add User Form --}}
    <div class="col-lg-4">
        <div class="admin-table-card">
            <div class="admin-table-header"><h6><i class="bi bi-person-plus" style="color:#818cf8;"></i> Tambah User Baru</h6></div>
            <div style="padding:20px;">
                <form method="POST" action="{{ route('admin.user.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.7rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#64748b;display:block;margin-bottom:7px;">Nama Lengkap</label>
                        <input type="text" name="name" class="admin-input w-100" placeholder="John Doe" value="{{ old('name') }}" required>
                        @error('name')<div style="font-size:.7rem;color:#f87171;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.7rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#64748b;display:block;margin-bottom:7px;">Alamat Email</label>
                        <input type="email" name="email" class="admin-input w-100" placeholder="john@example.com" value="{{ old('email') }}" required>
                        @error('email')<div style="font-size:.7rem;color:#f87171;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label" style="font-size:.7rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#64748b;display:block;margin-bottom:7px;">Password</label>
                        <input type="password" name="password" class="admin-input w-100" placeholder="min. 6 karakter" required>
                        @error('password')<div style="font-size:.7rem;color:#f87171;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn-admin-primary w-100">
                        <i class="bi bi-plus-lg me-1"></i> Tambah User
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- User Table --}}
    <div class="col-lg-8">
        <div class="admin-table-card">
            <div class="admin-table-header">
                <h6><i class="bi bi-people" style="color:#818cf8;"></i> Daftar User ({{ $users->total() }})</h6>
                <form method="GET" action="{{ route('admin.users') }}">
                    <input type="text" name="search" class="admin-input" style="width:200px;" placeholder="Cari nama / email…" value="{{ $search }}">
                </form>
            </div>
            <table class="admin-table">
                <thead>
                    <tr><th>#</th><th>Nama</th><th>Email</th><th>Dibuat</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="color:#64748b;">{{ $user->id }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:30px;height:30px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#fff;flex-shrink:0;">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td style="color:#64748b;">{{ $user->email }}</td>
                        <td style="color:#64748b;white-space:nowrap;">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.user.destroy', $user) }}" onsubmit="return confirm('Hapus user {{ addslashes($user->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-admin-danger"><i class="bi bi-trash3"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;color:#64748b;padding:30px;">Tidak ada user ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($users->hasPages())
            <div style="padding:16px 20px;border-top:1px solid var(--admin-border);">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
