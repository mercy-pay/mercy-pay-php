# Mercy Pay pour PHP

La bibliothèque PHP officielle de l'API [Mercy Pay](https://mercy-pay.com) : paiements mobile money et carte.

PHP 8.2 ou plus, avec `curl` et `json`. Aucune autre dépendance.

```sh
composer require mercy-pay/mercy-pay-php
```

Sur Laravel, prenez plutôt [`mercy-pay/laravel`](https://github.com/mercy-pay/laravel) : configuration, façade et route de webhooks prêtes.

## Encaisser un paiement

```php
use MercyPay\MercyPayClient;

$mercyPay = new MercyPayClient(getenv('MERCY_PAY_API_KEY'));

$session = $mercyPay->checkoutSessions->create([
    'amount' => 15000,
    'currency' => 'XAF',
    'description' => 'Commande 4821',
    'success_url' => 'https://boutique.example/merci',
    'metadata' => ['order_id' => '4821'],
]);

header('Location: '.$session->checkout_url);
```

Les montants sont en unités mineures. Chaque objet se lit en propriété ou en tableau : `$session->status` ou `$session['status']`. `toArray()` le rend en tableau.

## Recevoir les webhooks

```php
use MercyPay\Exception\SignatureVerificationException;
use MercyPay\Webhook;

$payload = file_get_contents('php://input');

try {
    $event = Webhook::constructEvent($payload, $_SERVER['HTTP_MERCYPAY_SIGNATURE'] ?? null, getenv('MERCY_PAY_WEBHOOK_SECRET'));
} catch (SignatureVerificationException) {
    http_response_code(400);
    exit;
}

if ($event->type === 'payment.succeeded') {
    $orderId = $event->data->metadata['order_id'];
    // Marquez la commande comme payée.
}

http_response_code(200);
```

Passez le corps brut, avant tout `json_decode`. Les événements inconnus de votre code sont à ignorer avec un `200`.

## Listes

Les listes arrivent par pages, les plus récents d'abord :

```php
foreach ($mercyPay->payments->all(['limit' => 100])->autoPagingIterator() as $payment) {
    // Toutes les pages, chargées au fur et à mesure.
}
```

## Erreurs

```php
use MercyPay\Exception\ApiConnectionException;
use MercyPay\Exception\InvalidRequestException;
use MercyPay\Exception\PaymentException;

try {
    $mercyPay->payouts->create($params, ['idempotency_key' => "payout-{$id}"]);
} catch (InvalidRequestException $e) {
    $e->getErrorCode(); // validation_failed, insufficient_balance…
    $e->getParam();     // le champ en cause
} catch (PaymentException $e) {
    // Refusé par l'opérateur ou la banque.
} catch (ApiConnectionException $e) {
    // Pas de réponse : relancez avec la même idempotency_key.
}
```

Toutes les exceptions héritent de `MercyPay\Exception\MercyPayException`. `getRequestId()` donne l'identifiant à citer au support.

## Nouvelles tentatives et idempotence

Une coupure réseau, un `429` ou une erreur `5xx` sont retentés deux fois. Chaque `POST` porte une `Idempotency-Key`, la même à chaque tentative : un paiement ou un retrait n'est jamais créé en double. Pour couvrir aussi vos propres relances, passez votre clé : `['idempotency_key' => "order-{$id}"]`.

## Options

```php
new MercyPayClient($apiKey, [
    'timeout' => 30,        // secondes
    'max_retries' => 2,
    'http_client' => $client, // votre MercyPay\HttpClient\ClientInterface
]);
```

## Sandbox

Une clé `mp_sandbox_…` ne touche pas à l'argent réel. `$mercyPay->testHelpers->triggerEvent(['type' => 'payment.succeeded'])` envoie un événement de test à vos endpoints. La [CLI](https://docs.mercy-pay.com/developers/cli) relaie les webhooks vers votre ordinateur.

## Documentation

La référence de chaque endpoint est sur [docs.mercy-pay.com](https://docs.mercy-pay.com). Le nom de chaque méthode suit celui de la référence : `$mercyPay->subscriptions->pause($id)`, `$mercyPay->webhookEndpoints->rotateSecret($id)`…

`tests/fixtures/openapi.json` est une copie de la référence : un test vérifie que chaque endpoint a sa méthode.
