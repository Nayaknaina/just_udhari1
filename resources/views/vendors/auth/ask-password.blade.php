<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Password</title>
</head>
<body>
    <h1>Please Confirm Your Password</h1>
    <form action="{{ url()->current() }}" method="POST">
        @csrf
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Submit</button>
        @if ($errors->has('password'))
            <div>{{ $errors->first('password') }}</div>
        @endif
    </form>
</body>
</html>
