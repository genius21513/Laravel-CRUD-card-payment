@extends('layouts.app')

@inject('banks', 'App\Const\Banks')

@section('content')
<div class="container">

    {{-- wyświetla się jak nie ma jeszcze żadnych kont --}}
    @if(($accounts->isEmpty()))
    <div class="row justify-content-center">
        <div class="col-3">
            <div class="card text-center">
                <div class="card-body">
                <h5 class="card-title">Nie masz jeszcze żadnych aktynych kont. Kliknij przycisk <strong>"Dodaj Konto"</strong> aby utworzyć nowe konto</h5>
                    <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAddAccount">
                        <i class="fas fa-plus"></i>
                        Dodaj Konto
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else

    @if(session()->has('message'))
        <div class="alert alert-secondary">
            {{ session()->get('message') }}
        </div>
    @endif

    {{-- Sekcja konta --}}
    <div class="row justify-content-end">
        <div class="col-6">
            <h2>Twoje konta osobiste</h2>
        </div>
        <div class="col-md-2 text-center">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAddAccount">
                <i class="fas fa-user-plus"></i>
                    Dodaj Konto
            </button>
        </div>
    </div>

    <div class="row justify-content-center">

        {{-- Wyświetlanie kafelka z kontem --}}
        @foreach ($accounts as $account)
            <div class="col-12 col-md-6 col-lg-4 mt-3">
                <div class="card text-center">
                    <div class="card-header account-header">
                        {{ $account->account_name }}
                        <button class="btn btn-secondary history-btn" data-id="{{ $account->id }}">Historia</button>
                    </div>
                    <div class="card-body">
                        <div class="account-body" id="account-body-{{ $account->id }}">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="row account">
                                        <div class="col-md-4 text-center">
                                            <button class="btn btn-info action-btn transaction-button show-transaction-form" data-id="{{ $account->id }}">
                                                <i class="fas fa-plus fa-lg"></i><br>
                                                przelew
                                            </button>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <button class="btn btn-warning action-btn send-account-id-button" data-id="{{ $account->id }}" data-bs-toggle="modal" data-bs-target="#modalLoan">
                                                <i class="fas fa-money-bill-wave fa-lg"></i><br>
                                                Weź pożyczkę
                                            </button>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <a class="btn btn-success action-btn send-account-id-button" data-id="{{ $account->id }}" data-bs-toggle="modal" data-bs-target="#modalAddCard">
                                                <i class="fas fa-plus fa-lg"></i><br>
                                                Dodaj Kartę
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                {{-- formularz do przelewu --}}
                                <li class="list-group-item hide" id="transaction-form-{{ $account->id }}">
                                    <form method="POST" action="{{ route('card.transfer') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="transaction-body">
                                            <div class="row mb-3">
                                                <label for="sender_card_id" class="col-md-12 col-form-label text-md-right">Wybierz kartę z której chcesz przesłać środki</label>
                                                <div class="col-md-5 input-group">
                                                    <select class="form-select" id="sender_card_id" name="sender_card_id">
                                                        <option selected>wybierz...</option>
                                                            @foreach($account->cards as $card)
                                                                    <option value="{{ $card->id }}" name="sender_card_id">{{ $card->card_number }} - Karta {{ $card->type }}({{ $card->balance }}zł)</option>
                                                            @endforeach
                                                      </select>
                                                    @error('sender_card_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="recipient_card_number" class="col-md-12 col-form-label text-md-right">Numer karty odbiorcy</label>
                                                <div class="col-md-5 input-group">
                                                    <input id="recipient_card_number" type="number" min="100000" maxlength="999999" class="recipient-card form-control @error('recipient_card_number') is-invalid @enderror" name="recipient_card_number" value="" required autocomplete="recipient_card_number" autofocus placeholder="przykład. 842824">

                                                    @error('recipient_card_number')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <span class="bank"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="amount" class="col-md-12 col-form-label text-md-right">Kwota przelewu</label>
                                                <div class="col-md-5 input-group">
                                                    <input id="amount" type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" name="amount" value="" required autocomplete="amount" aria-describedby="amount-addon" autofocus placeholder="przykład. 6000">
                                                    <span class="input-group-text" id="amount-addon">PLN</span>
                                                    @error('amount')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-danger show-transaction-form" data-id="{{ $account->id }}">Anuluj</button>
                                            <button type="submit" class="btn btn-success">Wpłać</button>
                                    </form>
                                </li>

                                {{-- karuzela z pożyczkami --}}
                                @if(!($account->loans->isEmpty()))
                                <li class="list-group-item">
                                    <div id="loanCarousel-{{ $account->id }}" class="carousel carousel-dark slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @php
                                            $loanSlides= array();
                                            @endphp                                            
                                            @foreach ($account->loans as $loan)
                                                @if($loan->amount > 0)
                                                <div class="slides-counter">{{ $loanSlides[] = 1 }}</div>
                                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                    <div class="carousel-caption d-md-block">
                                                    <h5>pożyczka</h5>
                                                    <p>{{ $loan->amount }} zł</p>
                                                    <h5>okres</h5>
                                                    <p>{{ $loan->period }} msc</p>
                                                    <button class="btn btn-warning show-loan-form" data-id="{{ $account->id }}" data-loan-id="{{ $loan->id }}">spłać</button>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        @if(count($loanSlides) > 1)
                                            <div class="carousel-indicators">
                                                @foreach ($loanSlides as $slide)
                                                    @if($loop->first)
                                                        <button type="button" data-bs-target="#loanCarousel-{{ $account->id }}" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                    @else
                                                        <button type="button" data-bs-target="#loanCarousel" data-bs-slide-to="{{ $loop->index }}" aria-label="Slide {{ $loop->iteration }}"></button>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#loanCarousel-{{ $account->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#loanCarousel-{{ $account->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                            </button>
                                        @endif
                                    </div>
                                </li>
                                @endif
                                 {{-- formularz do spłaty pożyczki --}}
                                 <li class="list-group-item hide" id="loan-form-{{ $account->id }}">
                                    <form method="post" action="{{ route('loan.repayment') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="transaction-body">
                                            <div class="row mb-3">
                                                <label for="card_id" class="col-md-12 col-form-label text-md-right">Wybierz kartę z której chcesz spłacić kredyt</label>
                                                <div class="col-md-5 input-group">
                                                    <select class="form-select" id="card_id" name="card_id">
                                                        <option selected>wybierz...</option>
                                                            @foreach($account->cards as $card)
                                                                    <option value="{{ $card->id }}" name="card_id">Karta {{ $card->type }}({{ $card->balance }}zł)</option>
                                                            @endforeach
                                                      </select>
                                                    @error('card_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="amount" class="col-md-12 col-form-label text-md-right">Kwota spłaty</label>
                                                <div class="col-md-5 input-group">
                                                    <input id="amount" type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" name="amount" value="" required autocomplete="amount" aria-describedby="amount-addon" autofocus placeholder="przykład. 200">
                                                    <span class="input-group-text" id="amount-addon">PLN</span>
                                                    @error('amount')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <input type="hidden" name="loan_id" value="">
                                            <button type="button" class="btn btn-danger show-loan-form" data-id="{{ $account->id }}">Anuluj</button>
                                            <button type="submit" class="btn btn-success">Spłać</button>
                                    </form>
                                </li>
                                {{-- karuzela z kartami --}}
                                @if(!($account->cards->isEmpty()))
                                <li class="list-group-item">
                                    <div id="cardCarousel-{{ $account->id }}" class="carousel carousel-dark slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @php
                                            $cardSlides= array();
                                            @endphp
                                            @foreach ($account->cards as $card)
                                            <div class="slides-counter">{{ $cardSlides[] = 1 }}</div>
                                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                    <div class="carousel-caption d-md-block">
                                                        <h3>{{ $card->card_number }}</h3>
                                                        <h5>karta {{ $card->type }}</h5>
                                                        <h6>{{ $card->balance }} zł</h6>
                                                        <button class="btn btn-success send-card-id-button" data-id="{{ $card->id }}" data-bs-toggle="modal" data-bs-target="#modalOwnTransfer">
                                                            wpłać środki
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if(count($cardSlides) > 1)
                                            <div class="carousel-indicators">
                                                @foreach ($cardSlides as $slide)
                                                    @if($loop->first)
                                                        <button type="button" data-bs-target="#cardCarousel-{{ $account->id }}" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                    @else
                                                        <button type="button" data-bs-target="#cardCarousel-{{ $account->id }}" data-bs-slide-to="{{ $loop->index }}" aria-label="Slide {{ $loop->iteration }}"></button>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#cardCarousel-{{ $account->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#cardCarousel-{{ $account->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                            </button>
                                        @endif
                                    </div>
                                </li>
                                @endif

                              </ul>
                        </div>
                        {{-- historia przelewów --}}
                        <div class="history-body hide" id="history-body-{{ $account->id }}">
                            @foreach ($account->transactions as $transaction)
                                <li class="list-group-item">
                                    <div class="sender-card-number">
                                        <strong>Przelew na kartę:</strong> <br>
                                        {{ $transaction->sender_card_number }}
                                    </div>
                                    <div class="recipient-card-number">
                                        <strong>Z karty:</strong> <br>
                                        {{ $transaction->recipient_card_number }}
                                    </div>
                                    <div class="transaction-date">
                                        <strong>Data transakcji:</strong> <br>
                                        {{ $transaction->transaction_date }}
                                    </div>
                                    <div class="amount">
                                        <strong>kwota:</strong> <br>
                                        {{ $transaction->amount }}
                                    </div>
                                </li>
                            @endforeach

                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        <a class="btn btn-danger" href="{{ route('account.delete', $account->id) }}"> Usuń konto</a>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    @endif()

    <!--  Modal own transfer -->
    <div class="modal fade" id="modalOwnTransfer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Zasilenie karty karty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('card.own.transfer') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="amount" class="col-md-4 col-form-label text-md-right">Wpłata własnych środków</label>
                                <div class="col-md-5 input-group">
                                    <input id="amount" type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" name="amount" value="" required autocomplete="balance" aria-describedby="amount-addon" autofocus placeholder="przykład. 6000">
                                    <span class="input-group-text" id="amount-addon">PLN</span>
                                    @error('amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <input type="hidden" name="card_id" value="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Anuluj</button>
                            <button type="submit" class="btn btn-success">Wpłać</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--  Loan repayment -->
    <div class="modal fade" id="modalRepayment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">spłata kredytu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('card.own.transfer') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="amount" class="col-md-4 col-form-label text-md-right">Wpłata własnych środków</label>
                                <div class="col-md-5 input-group">
                                    <input id="amount" type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" name="amount" value="" required autocomplete="balance" aria-describedby="amount-addon" autofocus placeholder="przykład. 6000">
                                    <span class="input-group-text" id="amount-addon">PLN</span>
                                    @error('amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <input type="hidden" name="card_id" value="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Anuluj</button>
                            <button type="submit" class="btn btn-success">Wpłać</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Modal -->
    <div class="modal fade" id="modalAddCard" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dodawanie karty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('card.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">

                                <label for="card_type" class="col-md-4 col-form-label text-md-right">Typ karty</label>
                                <div class="col-md-7">
                                    <div class="adding-card-radio row">
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" id="debit" type="radio" name="type" value="debit" required autofocus>
                                                <label class="form-check-label" for="debit">
                                                        Debetowa
                                                </label>
                                        </div>
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" id="credit" type="radio"  name="type" value="credit" required autofocus>
                                                <label class="form-check-label" for="credit">
                                                        Kredytowa
                                                </label>
                                        </div>
                                        @error('card_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="account_id" value="">

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Anuluj</button>
                            <button type="submit" class="btn btn-success">Dodaj Kartę</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Modal -->
    <div class="modal fade" id="modalAddAccount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dodawnie konta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('account.store', ['user_id' => $userId]) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="account_name" class="col-md-4 col-form-label text-md-right">Nazwa konta</label>

                            <div class="col-md-6">
                                <input id="account_name" type="text" class="form-control @error('account_name') is-invalid @enderror" name="account_name" value="" required autocomplete="account_name" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-success">Dodaj konto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loan Modal -->
    <div class="modal fade" id="modalLoan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Udzialnie Kredytu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('loan.store') }}" enctype="multipart/form-data">
                    @csrf
                        <div class="modal-body">

                            <div class="row mb-3">

                                <label for="amount" class="col-md-4 col-form-label text-md-right">Wysokość kredytu</label>
                                <div class="col-md-5 input-group">
                                    <input id="amount" type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" value="" required autocomplete="amount" aria-describedby="amount-addon" autofocus placeholder="przykład. 6000">
                                    <span class="input-group-text" id="amount-addon">PLN</span>
                                    @error('amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <label for="period" class="col-md-4 col-form-label text-md-right">Okres kredytowania</label>
                                <div class="col-md-5 input-group">
                                    <input id="period" type="number" class="form-control @error('period') is-invalid @enderror" name="period" value="" required autocomplete="period" aria-describedby="period-adon" autofocus placeholder="przykład. 12">
                                    <span class="input-group-text" id="period-adon">miesięcy</span>

                                    @error('period')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <input type="hidden" name="account_id" value="">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Anuluj</button>
                            <button type="submit" class="btn btn-warning">Weź pożyczkę</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js-files')
    <script src="{{ asset('js/modals-data.js') }}"></script>
    <script src="{{ asset('js/ajax-get-bank-name.js') }}"></script>
@endsection
