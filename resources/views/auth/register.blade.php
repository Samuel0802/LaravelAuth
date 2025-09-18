<x-layouts.main-layout pageTitle="Criar Conta">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card p-5">
                    <p class="display-6 text-center">CRIAR NOVA CONTA</p>

                    <form action="{{ route('store_user') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="username" class="form-label">Usuário</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}">

                            @error('username')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">

                            @error('email')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label">Data Nascimento</label>
                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}">

                            @error('data_nascimento')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gênero</label><br>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="genero" id="genero_masculino"
                                    value="masculino" {{ old('genero') == 'masculino' ? 'checked' : '' }}>
                                <label class="form-check-label" for="genero_masculino">Masculino</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="genero" id="genero_feminino"
                                    value="feminino" {{ old('genero') == 'feminino' ? 'checked' : '' }}>
                                <label class="form-check-label" for="genero_feminino">Feminino</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="genero" id="genero_outro"
                                    value="outro" {{ old('genero') == 'outro' ? 'checked' : '' }}>
                                <label class="form-check-label" for="genero_outro">Outro</label>
                            </div>

                            @error('genero')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password">

                            @error('password')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar senha</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">

                            @error('password')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <div class="mb-3">
                                    <a href="{{ route('login') }}">Já tenho conta de usuário</a>
                                </div>
                                <div>
                                    <a href="{{ route('forgot_password') }}">Esqueci a minha senha</a>
                                </div>
                            </div>
                            <div class="col text-end align-self-center">
                                <button type="submit" class="btn btn-secondary px-5">CRIAR CONTA</button>
                            </div>
                        </div>

                    </form>

                    @if (session('register_invalid'))
                        <div class="alert alert-danger text-center mt-3">
                            {{ sesion('register_invalid') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</x-layouts.main-layout>
