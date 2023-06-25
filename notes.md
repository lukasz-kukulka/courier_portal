Route::get('/', function () {
    return response( '<h1>xxxxx</h1>' )
    ->header( 'Content-Type', 'text/plain' );
    ->header( 'Content-Type', 'text/html' );
}); wywoluje strone / i dopdaje do niej hedera



Route::get('/post/{id}', function ($id) {
    dd( $id ); <<<<<<<<<------------------------ debuging lub ddd();
    return response( 'Post' . $id );
})->where( 'id', '[0-9]+' );