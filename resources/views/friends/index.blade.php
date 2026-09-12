<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-2xl font-black tracking-tight text-white">Amigos</h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="rpg-card p-6">
                <h3 class="text-xl font-bold text-white">Enviar solicitação</h3>
                <form method="POST" action="{{ route('friends.requests.store') }}" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-slate-200">Usuário</label>
                        <input id="user_id" name="user_id" type="number" min="1" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none" placeholder="ID do usuário">
                    </div>
                    <button type="submit" class="golden-button">Adicionar amigo</button>
                </form>
            </div>

            <div class="rpg-card p-6">
                <h3 class="text-xl font-bold text-white">Amigos</h3>
                @if ($friends->isEmpty())
                    <p class="mt-4 text-slate-300">Ainda não há amizades confirmadas.</p>
                @else
                    <ul class="mt-4 space-y-3">
                        @foreach ($friends as $friend)
                            <li class="rounded-xl border border-slate-800 bg-slate-950/60 p-3 text-slate-200">
                                {{ $friend->name }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-2">
            <div class="rpg-card p-6">
                <h3 class="text-xl font-bold text-white">Solicitações enviadas</h3>
                @if ($sentRequests->isEmpty())
                    <p class="mt-4 text-slate-300">Nenhuma solicitação pendente.</p>
                @else
                    <ul class="mt-4 space-y-3">
                        @foreach ($sentRequests as $request)
                            <li class="rounded-xl border border-slate-800 bg-slate-950/60 p-3 text-slate-200">
                                {{ $request->recipient->name }} · {{ $request->status }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="rpg-card p-6">
                <h3 class="text-xl font-bold text-white">Solicitações recebidas</h3>
                @if ($receivedRequests->isEmpty())
                    <p class="mt-4 text-slate-300">Nenhuma solicitação recebida.</p>
                @else
                    <ul class="mt-4 space-y-3">
                        @foreach ($receivedRequests as $request)
                            <li class="flex items-center justify-between gap-3 rounded-xl border border-slate-800 bg-slate-950/60 p-3 text-slate-200">
                                <span>{{ $request->sender->name }} · {{ $request->status }}</span>
                                @if ($request->status === 'pending')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('friends.requests.accept', $request) }}">
                                            @csrf
                                            <button type="submit" class="secondary-button text-xs px-3 py-2">Aceitar</button>
                                        </form>
                                        <form method="POST" action="{{ route('friends.requests.reject', $request) }}">
                                            @csrf
                                            <button type="submit" class="rounded-lg border border-red-500/50 px-3 py-2 text-xs font-semibold text-red-200">Recusar</button>
                                        </form>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="mt-8 rpg-card p-6">
            <h3 class="text-xl font-bold text-white">Convites de campanha</h3>
            @if ($campaignInvites->isEmpty())
                <p class="mt-4 text-slate-300">Você não recebeu convites para campanhas.</p>
            @else
                <ul class="mt-4 space-y-3">
                    @foreach ($campaignInvites as $invite)
                        <li class="flex flex-col gap-3 rounded-xl border border-slate-800 bg-slate-950/60 p-3 text-slate-200 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-semibold text-white">{{ $invite->campaign->name }}</p>
                                <p class="text-sm text-slate-400">Convidado por {{ $invite->inviter->name }} · {{ $invite->status }}</p>
                            </div>
                            @if ($invite->status === 'pending')
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('campaigns.invites.accept', $invite) }}">
                                        @csrf
                                        <button type="submit" class="secondary-button text-xs px-3 py-2">Aceitar</button>
                                    </form>
                                    <form method="POST" action="{{ route('campaigns.invites.reject', $invite) }}">
                                        @csrf
                                        <button type="submit" class="rounded-lg border border-red-500/50 px-3 py-2 text-xs font-semibold text-red-200">Recusar</button>
                                    </form>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
