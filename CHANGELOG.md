# Journal des changements

## 0.2.0 (18 novembre 2026)

- Chaque méthode décrit ses paramètres en forme de tableau PHPDoc, générée depuis la référence de l'API. **À vérifier** : `create()` et les autres méthodes qui ont des champs obligatoires attendent maintenant leur tableau, il n'a plus de valeur par défaut.
- Nouveau : `$mercyPay->embedDomains`, pour les sites autorisés du paiement intégré.
- Nouveau : `MercyPay\Catalog` (pays, opérateurs, types d'événements).
- Nouveau : `payments->waitForFinalStatus()` et `checkoutSessions->waitForCompletion()`.
- Nouveau : `testHelpers->mobileMoneyNumbers()` et `testHelpers->mobileMoneyNumber()`.

## 0.1.0 (16 novembre 2026)

- Première version : client pour toute l'API v1, pagination, erreurs typées, nouvelles tentatives et vérification des webhooks.
