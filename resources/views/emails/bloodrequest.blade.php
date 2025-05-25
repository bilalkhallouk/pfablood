@component('mail::message')
# Nouvelle demande de sang

Un patient a soumis une nouvelle demande de sang.

- **Groupe sanguin :** {{ $bloodRequest->blood_type }}
- **Unités nécessaires :** {{ $bloodRequest->units_needed }}
- **Ville :** {{ $bloodRequest->city }}
- **Centre :** {{ $bloodRequest->center_id }}

L'ordonnance est en pièce jointe.

@endcomponent
