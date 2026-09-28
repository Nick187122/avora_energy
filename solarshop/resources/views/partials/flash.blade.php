@if(session('success'))
    <div class="flash">
        <div class="alert alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.1V12a10 10 0 1 1-5.9-9.1"/><path d="m9 11 3 3L22 4"/></svg>
            {{ session('success') }}
        </div>
    </div>
@endif

@if($errors->any())
    <div class="flash">
        @foreach($errors->all() as $error)
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                {{ $error }}
            </div>
        @endforeach
    </div>
@endif

@if(session('error'))
    <div class="flash">
        <div class="alert alert-error">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
            {{ session('error') }}
        </div>
    </div>
@endif