<?php

namespace App\Http\Controllers;

use App\Models\CardFlagModel;
use App\Models\CreditCardModel;
use Illuminate\Http\Request;

class CreditCardController extends Controller
{
    private $creditCardModel;

    public function __construct(CreditCardModel $creditCardModel)
    {
        $this->creditCardModel = $creditCardModel;
    }

    public function index()
    {
        if (session('user')['firstAccess'] == 1) {
            return redirect('dashboard');
        }

        $cards = $this->creditCardModel->getCardsWithFlags();
        $flags = CardFlagModel::all();

        return view('cards.index', [
            'cards' => $cards,
            'flags' => $flags
        ]);
    }

    public function store()
    {
        $data = request()->all();

        if ($data['card_number']) {
            $cardStored = CreditCardModel::where('number', $data['card_number'])->first();

            if ($cardStored) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Cartão já registrado! Por favor, informe outro.'
                ]);
            }
        }

        CreditCardModel::create([
            'description' => mb_convert_case($data['card_description'], MB_CASE_TITLE, "UTF-8"),
            'number' => $data['card_number'],
            'flag' => $data['card_flag'],
            'user_id' => session('user')['id'],
            'invoice_day' => $data['invoice_day']
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Cartão cadastrado com sucesso!'
        ]);
    }

    public function show($id)
    {
        $card = $this->creditCardModel->getCardsWithFlagsById($id);

        if (!$card->number) {
            $card->number = 'Nenhum número informado';
            $card->no_number = true;
        } else {
            $card->number = substr_replace($card->number, '****-****-****-', 0, 15);
        }

        return response()->json($card);
    }

    public function update()
    {
        $data = request()->all();

        CreditCardModel::where('id', $data['id_card'])
            ->update([
                'description' => mb_convert_case($data['card_description_edit'], MB_CASE_TITLE, "UTF-8"),
                'flag' => $data['card_flag_edit'],
                'invoice_day' => $data['invoice_day']
            ]);

        return response()->json([
            'ok' => true,
            'message' => 'Categoria atualizada com sucesso'
        ]);
    }

    public function destroy($id)
    {
        CreditCardModel::where('id', $id)
            ->update([
                'active' => 0,
            ]);

        return response()->json([
            'ok' => true,
            'msg' => 'Cartão removido com sucesso!'
        ]);
    }
}
