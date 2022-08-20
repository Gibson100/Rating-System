@extends('layouts.app')

@section('content')

    @include('posts.addPost')


    @foreach($posts as $post)
        <div class="container mb-4">
            <div class="row">
                <div class="col-md-6 offset-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $post->title }}</h4>
                            <div class="img">
                                <img class="img-fluid"
                                     src="/storage/images/<?= $post['image']?>" ;
                                     alt="">
                            </div>
                            <p class=""><b>{{ $post->created_at->diffForHumans() }}</b></p>
                            <p class="card-text">{{ $post->content }}</p>
                            <a href="javascript:0;" class="card-link like-btn fas fa-thumbs-up {{ $post->id }}lk"
                               data-id="{{ $post->id }}"><span class="{{ $post->id }}">{{ $post->num_likes }}</span></a>
                            <a href="javascript:0;" class="card-link dislike-btn fas fa-thumbs-down {{ $post->id }}dk"
                               data-id="{{ $post->id }}"><span class="{{ $post->id }}">{{ $post->num_dislikes }}</span></a>
                        </div>
                        <div class="card-footer">
                            <a href="/posts/{{ $post->id }}/editPost"
                               class="btn btn-primary">Edit</a>
                            <a href="/post/{{ $post->id }}/delete" class="btn btn-primary">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>

    $(document).ready(function () {
        //allow laravel to accept ajax requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.like-btn').on('click', function (e) {

            e.preventDefault()
            const post_id = $(this).data('id');
            const like = "like";

            data = {
                'post_id': post_id,
                'likes': like,
            };


            @if(auth()->user())
            $.ajax({
                url: '/likes',
                type: 'post',
                data: data,
                success(response) {
                    console.log(response)
                    LoadLikes(post_id);

                }
            })
            @else
            alert("You need to login to like posts")
            @endif
        })

        $('.dislike-btn').on('click', function (e) {

            e.preventDefault()
            const post_id = $(this).data('id');
            const dislike = "dislike"

            const data = {
                'post_id': post_id,
                'likes': dislike,
            }

            @if(auth()->user())
            $.ajax({
                url: '/likes',
                type: 'post',
                data: data,
                success(response) {
                    console.log(response)
                    LoadLikes(post_id);
                }
            })
            @else
            alert("You need to log in to dislike posts")
            @endif
        })

        function LoadLikes(post_id) {

            const data = {
                'post_id': post_id
            }
            $.ajax({
                url: '/getLikes',
                type: 'post',
                data: data,
                success(response) {
                    console.log(response)

                    $("." + post_id + "lk").html((response.likes));
                    $("." + post_id + "dk").html((response.dislikes));
                }
            })
        }
    });
</script>
