<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;

class CustomerValidationController extends Controller
{
    /**
     * Проверка данных, при регистрации клиента
     */
    public function validateCustomer(Request $request)
    {
        // Определяем правила
        $rules = [
            'email' => 'required|email|unique:customers,email',
            'phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'],
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:150',
            'day_birthday' => 'nullable|date',
        ];

        // Кастомные сообщения об ошибках
        $messages = [
            'email.required' => 'Введите email',
            'email.email' => 'Некорректный формат email',
            'email.unique' => 'Такой email уже зарегистрирован',
            'phone.required' => 'Введите телефон',
            'phone.regex' => 'Некорректный формат телефона',
            'first_name.required' => 'Введите имя',
        ];

        // Запускаем валидацию
        $validator = Validator::make($request->all(), $rules, $messages);

        // Если есть ошибки — возвращаем JSON с ошибками
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Если всё корректно — можно создать клиента
        $customer = Customer::create([
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Создаем описание
        $customer->description()->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'city' => $request->city,
            'day_birthday' => $request->day_birthday,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Клиент успешно добавлен!',
            'customer_id' => $customer->id,
        ]);
    }
}
