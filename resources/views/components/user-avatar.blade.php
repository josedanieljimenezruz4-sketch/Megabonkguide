@props(['user', 'size' => '40', 'class' => ''])

@php
    $sizePx = $size . 'px';
    
    // Logic to determine image source
    $avatarUrl = asset('images/default-avatar.png');
    
    if ($user && $user->avatar) {
        if (str_starts_with($user->avatar, 'http')) {
            $avatarUrl = $user->avatar;
        } else {
            $avatarUrl = asset('storage/avatars/' . $user->avatar);
        }
    }
@endphp

<img src="{{ $avatarUrl }}" 
     alt="Avatar" 
     style="width: {{ $sizePx }}; height: {{ $sizePx }}; border-radius: 50%; object-fit: cover;" 
     class="{{ $class }}">
