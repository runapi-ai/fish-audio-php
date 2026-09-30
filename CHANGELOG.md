# Changelog

## [v0.5.0](https://github.com/runapi-ai/fish-audio-php/releases/tag/v0.5.0) - 2026-09-30

### Changed
- Send request parameters to the service without local validation. Model ids and parameter values the service supports work without an SDK upgrade; static types and enum constants remain for completion.
  Migration: Invalid parameters now throw `ValidationException` built from the service's 400 response, including its status and message, instead of a `ValidationException` thrown locally before the request.


## [v0.4.2](https://github.com/runapi-ai/fish-audio-php/releases/tag/v0.4.2) - 2026-09-28

### Added
- Return usage.cost as a float USD amount on completed async Task query and webhook envelopes.

### Removed
- Remove the public Task billing object from Task envelopes.
  Migration: Read usage.cost on completed Task envelopes. Create, processing, and failed envelopes omit usage.


## [v0.4.1](https://github.com/runapi-ai/fish-audio-php/releases/tag/v0.4.1) - 2026-09-04

### Changed
- Return terminal speech and voice responses whether the request completes directly or through an accepted Task.


## [v0.4.0](https://github.com/runapi-ai/fish-audio-php/releases/tag/v0.4.0) - 2026-08-21

### Added
- Add typed create, list, and get resources for account-owned reusable voices.
- Accept trained account-owned voice IDs in s1, s2-pro, and s2.1-pro text-to-speech requests.


## [v0.3.0](https://github.com/runapi-ai/fish-audio-php/releases/tag/v0.3.0) - 2026-08-17

### Added
- Add typed create, list, and get resources for account-owned reusable voices.
- Accept trained account-owned voice IDs in s1, s2-pro, and s2.1-pro text-to-speech requests.


## [v0.2.0](https://github.com/runapi-ai/fish-audio-php/releases/tag/v0.2.0) - 2026-08-07

### Added
- Add s2.1-pro with managed MP3 and WAV output controls.


## [v0.1.2](https://github.com/runapi-ai/fish-audio-php/releases/tag/v0.1.2) - 2026-07-28

### Added
- Decode typed Task Billing Facts on synchronous text-to-speech responses.


## [v0.1.1](https://github.com/runapi-ai/fish-audio-php/releases/tag/v0.1.1) - 2026-07-21

### Added
- Accept typed inline reference audio entries in synchronous text-to-speech requests.


## [v0.1.0](https://github.com/runapi-ai/fish-audio-php/releases/tag/v0.1.0) - 2026-07-20

### Added
- Add a synchronous PHP text-to-speech client with typed managed MP3 metadata.
