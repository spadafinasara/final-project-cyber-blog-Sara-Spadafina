<x-layout>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Il mio profilo</h2>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nuova password (lascia vuoto per non cambiarla)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Salva modifiche</button>
                </form>
            </div>
        </div>
    </div>
</x-layout>