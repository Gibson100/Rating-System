@extends('layouts.app')

@section('content')

    <!-- Add post Modal -->
    <div class="modal hide fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit Post</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="/edit/{{ $post->id }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="form-group row">
                            <label for="caption" class="col-md-4 col-form-label">Post Title</label>
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror"
                                   name="title" value="{{ $post->title }}" value="{{ old('title') }}" autocomplete="caption" autofocus>

                            @error('title')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label for="content" class="col-md-4 col-form-label">Post Content</label>
                            <input id="content" type="text" class="form-control @error('content') is-invalid @enderror"
                                   name="content" value="{{ $post->content }}" value="{{ old('content') }}" autocomplete="content" autofocus>

                            @error('content')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>

                        <div class="row">
                            <label for="image" class="col-md-4 col-form-label">Post Image</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                            @error('image')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button>
                            <a href="/" type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- End of post modal -->
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(window).on('load', function() {
        $('#editModal').modal('show');
    });
</script>
