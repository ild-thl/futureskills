# Changelog

## [Unreleased]

## 2021-04-07/dk
### Added
- offer relations can be saved via offer editing
### Changed
- Author saving fixed

## 2021-04-03/dk
### Changed
- Offers cascade on delete
- fixed competence saving process
- moved external update route

## 2021-03-31/dk
### Changed
- Added identifiers and descriptions via migration
### Added
- new route for filter tags: /filter/tags

## 2021-03-15/dk
### Added
- Offer_relations table
- Migrations for DB redesign
### Changed
- OfferController and -Requests regarding redesign
## 2021-02-15/ml
### Added
- CookieController, Cookie for the Bot-UserID
- New Package in Composer (php-jwt)

### Changed
- BotConfigController, generates Token fpr Secure Bot
- New Routes

## 2021-02-02/ml
### Added
- BotConfigController.php, reads static JSON File for Bot-Konfiguration (Urls in config/bot.php)

## 2021-01-21/dk
### Added
- New migration for adding external id and status to Offer
- New route for editing offers by external ids
- Special rules for external id OfferUpdateRequest

## [Released] v0.1.2

## 2020-12-03/dk
### Changed
- Fixed offer sorting on offer->index
- added and fixed some riles for offer storing and updating

## [Released] v0.1.1

## 2020-12-03/dk
### Added
- doctrine/dbal for DB migration changes of columns
### Changed
- Offer model has more fields and returns more data after storage
- Subscription table is deleted and created without constraints
- Set lcobucci/jwt to 3.3.2 for compatibility
- offer index is returned sorted by sort_flag

## [Released] v0.1

## 2020-11-10/dk
### Added
- New routes for offers and institutions without authentication

