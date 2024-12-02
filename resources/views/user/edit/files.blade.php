<table class="table text-center table-bordered">
    <thead>
        <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Archivo</th>
            <th scope="col">Fecha de expiración</th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (!$user->files->isEmpty())
            @foreach ($user->files as $file)
                <tr>
                    <td class="align-middle">{{ $file->filename->name }}</td>
                    <td class="align-middle">
                        <a href="{{ route('user.file.download', ['id' => $file->id]) }}"
                            class="btn btn-link{{ $file->verifyPath() ? '' : ' disabled' }}">
                            {{ $file->verifyPath() ? 'Archivo firma' : 'Sin archivo' }}
                        </a>
                    </td>
                    <td class="align-middle">{{ $file->expirated_at }}</td>
                    <td class="align-middle">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#filesModal" onclick="setFileId({{ $file->id }}, '{{$file->filename->name}}')">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<a class="text-link" href="https://onlinesignature.com/draw-a-signature-online" target="_blank"> Para dibujar la firma y descargar la imagen, haz click aqui </a>
