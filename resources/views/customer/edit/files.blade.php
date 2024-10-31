<table class="table text-center table-bordered">
    <thead>
        <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Archivo</th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (!$customer->files->isEmpty())
            @foreach ($customer->files as $file)
                <tr>
                    <td class="align-middle">{{ $file->filename->name }}</td>
                    <td class="align-middle">
                        <a href="{{ route('customer.file.download', ['id' => $file->id]) }}"
                            class="btn btn-link{{ $file->path ? '' : ' disabled' }}">
                            {{ $file->filename->name . '.pdf' ?? 'Sin documento' }}
                        </a>
                    </td>
                    <td class="align-middle">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-toggle="modal" data-bs-target="#filesModal"
                            onclick="setFileId({{ $file->id }})">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

