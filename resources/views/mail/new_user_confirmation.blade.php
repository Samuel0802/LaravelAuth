{{-- Conteudo do Email para User--}}

<h3>Confirmação de registro de Usuário</h3>
<p>Caro, {{ $username }}, para concluir o seu registro de usuário, clique no link abaixo:</p>

<p><a href="{{ $confirmation_link }}" class="btn btn-primary">Confirmar Registro</a></p>

<p>Atenciosamente</p>
<p>Equipe de suporte</p>
