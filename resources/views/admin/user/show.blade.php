<x-admin-app-layout :title="__('User Details')">
    <div class="py-6 flex justify-between">
        <div class="text-3xl">{{ __('User Details') }}</div>
        <div>
            <a class="text-primary-700 underline font-semibold"
               href="{{ route('admin.user.index') }}">{{ __('Users') }}</a>
        </div>
    </div>

    <div class="w-full bg-white flex flex-wrap justify-end p-4">
        <div class="w-full md:w-1/2 lg:w-1/3 flex justify-center p-2">
            <img class="h-64 w-64" src="{{ $user->avatar }}" alt="Avatar of {{ $user->name }}"/>
        </div>
        <div class="w-full md:w-1/2 lg:w-1/3">
            <table>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Phone') }}</td>
                    <td class="p-2">{{ $user->phone }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Name') }}</td>
                    <td class="p-2">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Status') }}</td>
                    <td class="p-2">{{ $user->status->key }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Rank') }}</td>
                    <td class="p-2">{{ $user->rank->key }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Referrer') }}</td>
                    <td class="p-2">{{ optional($user->referrer)->name }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Placement') }}</td>
                    <td class="p-2">{{ optional($user->placement)->name }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Phone Verified') }}</td>
                    <td class="p-2 flex">
                        @if($user->hasVerifiedPhone())
                            <div
                                class="rounded bg-green-300 py-1 px-2 text-xs font-semibold text-green-800">{{ __('Yes') }}</div>
                        @else
                            <div
                                class="rounded bg-red-300 py-1 px-2 text-xs font-semibold text-red-800">{{ __('No') }}</div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Email') }}</td>
                    <td class="p-2">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Email Verified') }}</td>
                    <td class="p-2 flex">
                        @if($user->hasVerifiedEmail())
                            <div
                                class="rounded bg-green-300 py-1 px-2 text-xs font-semibold text-green-800">{{ __('Yes') }}</div>
                        @else
                            <div
                                class="rounded bg-red-200 py-1 px-2 text-xs font-semibold text-red-800">{{ __('No') }}</div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Is Executive') }}</td>
                    <td class="p-2 flex">
                        @if($user->rank->isNot(\App\Enums\UserRank::None()))
                            <div
                                class="rounded bg-green-300 py-1 px-2 text-xs font-semibold text-green-800">{{ __('Yes') }}</div>
                        @else
                            <div
                                class="rounded bg-red-300 py-1 px-2 text-xs font-semibold text-red-800">{{ __('No') }}</div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="w-full md:w-1/2 lg:w-1/3">
            <table>
                @foreach(\App\Lib\Wallets\WalletManager::all() as $wallet)
                    <tr>
                        <td class="p-2 font-semibold">{{ $wallet->getName() }} Balance</td>
                        <td class="p-2">{{ $wallet->getBalanceFor($user) }}</td>
                    </tr>
                    <tr>
                        <td class="p-2 font-semibold">{{ $wallet->getName() }} Total In</td>
                        <td class="p-2">{{ $wallet->getTotalInFor($user) }}</td>
                    </tr>
                    <tr>
                        <td class="p-2 font-semibold">{{ $wallet->getName() }} Total Out</td>
                        <td class="p-2">{{ $wallet->getTotalOutFor($user) }}</td>
                    </tr>
                    @if($wallet instanceof \App\Lib\Wallets\CashInAble)
                        <tr>
                            <td class="p-2 font-semibold">{{ $wallet->getName() }} Cash In</td>
                            <td class="p-2">{{ $wallet->getTotalCashInAmountFor($user) }}</td>
                        </tr>
                    @endif
                    @if($wallet instanceof \App\Lib\Wallets\WithdrawAble)
                        <tr>
                            <td class="p-2 font-semibold">{{ $wallet->getName() }} Withdraw</td>
                            <td class="p-2">{{ $wallet->getTotalWithdrawAmountFor($user) }}</td>
                        </tr>
                    @endif
                    @if($wallet instanceof \App\Lib\Wallets\TransferAble)
                        <tr>
                            <td class="p-2 font-semibold">{{ $wallet->getName() }} Received</td>
                            <td class="p-2">{{ $wallet->totalTransferInFor($user) }}</td>
                        </tr>
                        <tr>
                            <td class="p-2 font-semibold">{{ $wallet->getName() }} Send</td>
                            <td class="p-2">{{ $wallet->totalTransferOutFor($user) }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>

    <div class="w-full my-8">
        <div class="bg-white" x-data="sendMailData">
            <div class="p-4 border border-b text-lg">Send email to user 📧</div>
            <x-labeled-input class="p-4" name="subject" x-model="subject"/>
            <div class="flex">
                <div class="w-1/2 p-4">
                    <textarea name="mail" rows="20" id="mail" x-model="content" class="w-full"></textarea>
                </div>
                <div class="w-1/2 p-4 overflow-auto">
                    <div class="isolate-css" x-html="marked.parse(content)"></div>
                </div>
            </div>
            <div class="w-full p-4 text-center">
                <x-button x-bind:disabled="sending" x-on:click="sendMail">{{ __('Send') }}</x-button>
            </div>
        </div>
    </div>

    <script src="//cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sendMailData', () => ({
                content: '# hello user',
                subject: 'Subject',
                sending: false,
                sendMail() {
                    if (this.subject.length && this.content.length) {
                        this.sending = true
                        axios.post(
                            '{{ route('api.sendmail.user') }}',
                            {
                                user_id: '{{ encrypt($user->id) }}',
                                subject: this.subject,
                                content: this.content,
                            }
                        ).then(() => {
                            alert('Mail sent successfully')
                            this.subject = ''
                            this.content = ''
                        }).catch(() => {
                            alert('Error while sending mail. Contact to webmaster')
                        }).finally(() => {
                            this.sending = false
                        })
                    } else {
                        alert('Content or subject is empty')
                    }
                },
            }))
        })
    </script>
</x-admin-app-layout>
