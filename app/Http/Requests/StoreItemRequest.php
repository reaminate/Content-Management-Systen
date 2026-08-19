<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Item;
class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $item = $this->route('item');
        $menuId = $this->input('menu_id', $item->menu_id);

        $siblings = Item::where('menu_id', $menuId)->where('id', '!=', $item->id);
        $max = (clone $siblings)->max('order') ?? 0;
        return [
            'label' => ['string', 'unique:items,label', 'required'],
            'order' => ['integer', "gt:$max", 'required'],
            'menu_id'=> ['integer', 'exists:menus,id', 'required'],
            'page_id'=> ['integer', 'sometimes', 'exists:page,id']
        ];
    }
}
