@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <style>
        .sidebar {
            color: white;
            text-decoration: none
        }

        .sidebar:hover {
            background-color: #e9ecef;
            color: #212529;
        }

        .directory:hover {
            text-decoration: underline !important;
            color: #0d6efd !important;
        }
    </style>

    <div class="row w-100 justify-content-between m-0 h-100">
        @include('client.directory.navbar')
        <div class="col-11">
            <div class="row p-3">
                <div class="col-12 mb-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @foreach ($links as $i => $link)
                                <li class="breadcrumb-item">
                                    @if ($i == 0)
                                        <a href="{{ route('client.system.index', ['path' => $link]) }}">Inicio</a>
                                    @else
                                        @if (count($links) != $i + 1)
                                            <a
                                                href="{{ route('client.system.index', ['path' => $link]) }}">{{ basename($link) }}</a>
                                        @else
                                            {{ basename($link) }}
                                        @endif
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                    @can('write_client')
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#directoryModal">
                            <i class="bi bi-folder-fill"></i> Crear carpeta
                        </button>

                        <a href="{{ route('client.directory.mip', ['path' => $data['root_path']]) }}"
                            class="btn btn-warning btn-sm"
                            onclick="return confirm('{{ __('messages.do_you_want_create_mip') }}')">
                            <i class="bi bi-folder-plus"></i> {{ __('client.title.create_mip') }}
                        </a>

                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#fileModal">
                            <i class="bi bi-file-earmark-arrow-up-fill"></i> {{ __('client.title.create_file') }}
                        </button>
                    @endcan
                </div>
                <div class="col-12">
                    <div class="list-group">
                        @foreach ($data['mip_directories'] as $dir)
                            @if ($user->hasDirectory($dir['path']) || in_array($user->work_department_id, [1, 7]))
                                <div class="list-group-item d-flex align-items-center justify-content-between p-0">
                                    <a href="{{ route('client.system.index', ['path' => $dir['path']]) }}"
                                        class="icon-link d-block text-decoration-none inline p-2 w-75">
                                        <i class="bi bi-folder-fill text-warning"></i> {{ $dir['name'] }}
                                    </a>
                                </div>
                            @endif
                        @endforeach

                        @foreach ($data['directories'] as $dir)
                            @if ($user->hasDirectory($dir['path']) || in_array($user->work_department_id, [1, 7]))
                                <div class="list-group-item d-flex align-items-center justify-content-between p-0">
                                    <a href="{{ route('client.system.index', ['path' => $dir['path']]) }}"
                                        class="icon-link d-block text-decoration-none inline p-2 w-75">
                                        <i class="bi bi-folder-fill text-warning"></i> {{ $dir['name'] }}
                                    </a>
                                    @can('write_client')
                                        <div class="d-flex gap-2 me-2">
                                            {{-- <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#permissionsModal" onclick="setChecked({{ $root->id }})"><i
                                                class="bi bi-person-fill-gear"></i> {{ __('buttons.permissions') }}</button> --}}
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editDirectoryModal"
                                                onclick="setRoot('{{ $dir['name'] }}', '{{ $dir['path'] }}')"><i
                                                    class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}</button>
                                            <a href="{{ route('client.directory.destroy', ['path' => $dir['path']]) }}"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                                                <i class="bi bi-trash-fill"></i> {{ __('buttons.delete') }}
                                            </a>
                                        </div>
                                    @endcan
                                </div>
                            @endif
                        @endforeach

                        @foreach ($data['mip_files'] as $file)
                            <div class="list-group-item d-flex align-items-center justify-content-between p-0">
                                <a href="{{ route('client.file.download', ['path' => $file['path']]) }}"
                                    class="icon-link d-block text-decoration-none inline p-2 w-75">
                                    <i class="bi bi-file-pdf-fill text-danger fs-5"></i> {{ $file['name'] }}
                                </a>
                            </div>
                        @endforeach

                        @foreach ($data['files'] as $file)
                            <div class="list-group-item d-flex align-items-center justify-content-between p-0">
                                <a href="{{ route('client.file.download', ['path' => $file['path']]) }}"
                                    class="icon-link d-block text-decoration-none inline p-2 w-75">
                                    <i class="bi bi-file-pdf-fill text-danger fs-5"></i> {{ $file['name'] }}
                                </a>

                                @can('write_client')
                                    <div class="btn-group me-2">
                                        <a href="{{ route('client.file.destroy', ['path' => $file['path']]) }}"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                                            <i class="bi bi-trash-fill"></i> {{ __('buttons.delete') }}
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('client.modals.create.directory')
    @include('client.modals.edit.directory')
    @include('client.modals.create.file')

    {{-- @include('client.modals.edit.permissions') --}}

    {{-- <script>
        const permissions = @json($permissions);
    </script> --}}
@endsection
