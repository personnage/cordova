{{-- When apply filter, exclude the sort, pagination and search. --}}
<ul class="nav nav-pills">
    <li role="presentation" @unless(Request::has('filter')) class="active" @endunless>
        <a href="{{ url('admin/user') }}">Active <span class="badge">{{ $activeCount }}</span></a>
    </li>

    <li role="presentation" @if(request('filter')=='admins') class="active" @endif>
        <a href="{{ url('admin/user').'?filter=admins' }}">Admins <span class="badge">{{ $adminsCount }}</span></a>
    </li>

    <li role="presentation" @if(request('filter')=='deleted') class="active" @endif>
        <a href="{{ url('admin/user').'?filter=deleted' }}">Deleted <span class="badge">{{ $deletedCount }}</span></a>
    </li>
</ul>

