{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('school-year') }}"><i class="nav-icon la la-graduation-cap"></i> Tahun Ajaran</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('semester') }}"><i class="nav-icon la la-columns"></i> Semester</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('member') }}"><i class="nav-icon la la-users"></i> Guru</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('book') }}"><i class="nav-icon la la-book"></i> Buku</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('transaction') }}"><i class="nav-icon la la-folder-plus"></i> Peminjaman Buku</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('book-lack') }}"><i class="nav-icon la la-folder-minus"></i> Kekurangan Buku</a></li>