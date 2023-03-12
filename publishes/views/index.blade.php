<!DOCTYPE html>
<html lang="{{config('app.locale')}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tugiclient 1.0</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ '/public/tugiclient/css/tugiclient.css' }}">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script src="{{ '/public/tugiclient/js/tugiclient.js' }}"></script>


</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @foreach($data['navigation'] as $key => $menu)
                        @if($menu['children'])
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="{{$menu['permalink']}}" id="navbarDropdown{{$key}}" role="button" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    {{$menu['title']}}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{$key}}">
                                    @foreach($menu['children'] as $key => $menu)
                                        <li><a href="{{$menu['permalink']}}" class="dropdown-item">{{$menu['title']}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{$menu['permalink']}}" class="nav-link" aria-current="page">{{$menu['title']}}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

</header>


<main role="main" class="container">
    <h1>{{$data['page']['title']}}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach($data['breadcrumb'] as $key => $breadcrumb)
            <li class="breadcrumb-item"><a href="{{$breadcrumb['permalink']}}">{{$breadcrumb['title']}}</a></li>
            @endforeach
        </ol>
    </nav>





</main>



</body>
</html>
