<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getCart()
    {
        return Session::get('cart', []);
    }

    public function addToCart($productId, $quantity = 1)
    {
        $product = $this->productRepository->findOrFail($productId);

        if ($product->stock < $quantity) {
            throw new \Exception('Stock insuficiente');
        }

        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;

            if ($product->stock < $newQuantity) {
                throw new \Exception('Stock insuficiente');
            }

            $cart[$productId]['quantity'] = $newQuantity;
            $cart[$productId]['subtotal'] = $product->final_price * $newQuantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->final_price,
                'quantity' => $quantity,
                'subtotal' => $product->final_price * $quantity,
                'image' => $product->primaryImage?->image_url,
            ];
        }

        Session::put('cart', $cart);

        return $this->getCartWithTotals();
    }

    public function updateQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($productId);
        }

        $product = $this->productRepository->findOrFail($productId);

        if ($product->stock < $quantity) {
            throw new \Exception('Stock insuficiente');
        }

        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            throw new \Exception('Producto no encontrado en el carrito');
        }

        $cart[$productId]['quantity'] = $quantity;
        $cart[$productId]['subtotal'] = $product->final_price * $quantity;

        Session::put('cart', $cart);

        return $this->getCartWithTotals();
    }

    public function removeFromCart($productId)
    {
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        return $this->getCartWithTotals();
    }

    public function clearCart()
    {
        Session::forget('cart');
        return [];
    }

    public function getCartWithTotals()
    {
        $cart = $this->getCart();
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cart as $item) {
            $subtotal += $item['subtotal'];
            $totalItems += $item['quantity'];
        }

        $tax = $subtotal * 0.19; // 19% IVA Colombia
        $total = $subtotal + $tax;

        return [
            'items' => array_values($cart),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'total_items' => $totalItems,
        ];
    }

    public function getCartCount()
    {
        $cart = $this->getCart();
        $count = 0;

        foreach ($cart as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }
}
