{{-- Conteudo do Email para User--}}

<h3>Recuperação de  Senha</h3>
<p>Caro <strong>{{ $username }}</strong>, Para recuperar a senha de usuário, clique no link a abaixo:</p>

<p>Tempo limite para realizar a recuperação de senha é de <strong>60 Minutos</strong></p>

<p><a href="{{ $token_link }}" class="btn btn-primary">Recuperar a Senha</a></p>

<p>Atenciosamente</p>
<p>Equipe de suporte</p>
