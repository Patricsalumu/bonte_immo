<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use App\Models\Immeuble;
use App\Models\Loyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('gestionnaire');
    }

    public function index()
    {
        $immeubles = Immeuble::where('actif', true)->get();
        $locatairesEnRetard = Locataire::whereHas('loyers', function($query) {
            $query->where('statut', 'en_retard');
        })->get();
        
        return view('notifications.index', compact('immeubles', 'locatairesEnRetard'));
    }

    public function envoyerRappel(Request $request)
    {
        $request->validate([
            'destinataires' => 'required|string',
            'type_destinataires' => 'required|in:tous,immeuble,retards,selection',
            'immeuble_id' => 'nullable|exists:immeubles,id',
            'locataires_selectionnes' => 'nullable|array',
            'objet' => 'required|string|max:255',
            'message' => 'required|string',
            'mode_envoi' => 'required|in:sms,email,whatsapp'
        ]);

        $locataires = collect();

        // Déterminer les destinataires selon le type
        switch ($request->type_destinataires) {
            case 'tous':
                $locataires = Locataire::where('actif', true)->get();
                break;
                
            case 'immeuble':
                $locataires = Locataire::whereHas('loyers', function($query) use ($request) {
                    $query->whereHas('appartement', function($subQuery) use ($request) {
                        $subQuery->where('immeuble_id', $request->immeuble_id);
                    });
                })->where('actif', true)->get();
                break;
                
            case 'retards':
                $locataires = Locataire::whereHas('loyers', function($query) {
                    $query->where('statut', 'en_retard');
                })->where('actif', true)->get();
                break;
                
            case 'selection':
                if ($request->locataires_selectionnes) {
                    $locataires = Locataire::whereIn('id', $request->locataires_selectionnes)
                                          ->where('actif', true)
                                          ->get();
                }
                break;
        }

        if ($locataires->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun locataire trouvé pour les critères sélectionnés.');
        }

        $nbEnvoyes = 0;
        $erreurs = [];

        foreach ($locataires as $locataire) {
            try {
                switch ($request->mode_envoi) {
                    case 'sms':
                        $this->envoyerSMS($locataire, $request->objet, $request->message);
                        break;
                        
                    case 'email':
                        if ($locataire->email) {
                            $this->envoyerEmail($locataire, $request->objet, $request->message);
                        } else {
                            $erreurs[] = "Pas d'email pour {$locataire->nom} {$locataire->prenom}";
                            continue 2;
                        }
                        break;
                        
                    case 'whatsapp':
                        $this->envoyerWhatsApp($locataire, $request->objet, $request->message);
                        break;
                }
                
                $nbEnvoyes++;
                
                // Log de l'envoi
                \Log::info("Notification envoyée", [
                    'locataire_id' => $locataire->id,
                    'mode' => $request->mode_envoi,
                    'objet' => $request->objet,
                    'user_id' => auth()->id()
                ]);
                
            } catch (\Exception $e) {
                $erreurs[] = "Erreur pour {$locataire->nom} {$locataire->prenom}: " . $e->getMessage();
            }
        }

        $message = "Notifications envoyées avec succès à {$nbEnvoyes} locataire(s).";
        if (!empty($erreurs)) {
            $message .= " Erreurs: " . implode(', ', $erreurs);
        }

        return redirect()->route('notifications.index')->with('success', $message);
    }

    private function envoyerSMS($locataire, $objet, $message)
    {
        // Simulation d'envoi SMS
        // Dans un vrai projet, intégrer avec un service SMS comme Twilio, Nexmo, etc.
        
        $messageComplet = "{$objet}\n\n{$message}\n\nCordialement,\nLa Bonte Immo";
        
        // Log pour simulation
        \Log::info("SMS envoyé à {$locataire->telephone}: {$messageComplet}");
        
        // Ici vous intégreriez votre service SMS
        // Exemple avec Twilio:
        // $twilio = new Client($sid, $token);
        // $twilio->messages->create($locataire->telephone, ['from' => $from, 'body' => $messageComplet]);
        
        return true;
    }

    private function envoyerEmail($locataire, $objet, $message)
    {
        // Simulation d'envoi email
        // Dans un vrai projet, configurer le service mail dans config/mail.php
        
        $donnees = [
            'locataire' => $locataire,
            'objet' => $objet,
            'message' => $message
        ];
        
        // Log pour simulation
        \Log::info("Email envoyé à {$locataire->email}: {$objet}");
        
        // Ici vous intégreriez votre service email
        // Mail::send('emails.rappel', $donnees, function($mail) use ($locataire, $objet) {
        //     $mail->to($locataire->email)->subject($objet);
        // });
        
        return true;
    }

    private function envoyerWhatsApp($locataire, $objet, $message)
    {
        // Simulation d'envoi WhatsApp
        // Dans un vrai projet, intégrer avec l'API WhatsApp Business
        
        $messageComplet = "{$objet}\n\n{$message}\n\nCordialement,\nLa Bonte Immo";
        
        // Log pour simulation
        \Log::info("WhatsApp envoyé à {$locataire->telephone}: {$messageComplet}");
        
        // Ici vous intégreriez l'API WhatsApp Business
        
        return true;
    }

    public function getLocatairesByImmeuble(Request $request)
    {
        $immeubleId = $request->get('immeuble_id');
        
        $locataires = Locataire::whereHas('loyers', function($query) use ($immeubleId) {
            $query->whereHas('appartement', function($subQuery) use ($immeubleId) {
                $subQuery->where('immeuble_id', $immeubleId);
            });
        })->where('actif', true)->get(['id', 'nom', 'prenom', 'telephone']);

        return response()->json($locataires);
    }

    public function getLocatairesEnRetard()
    {
        $locataires = Locataire::whereHas('loyers', function($query) {
            $query->where('statut', 'en_retard');
        })->where('actif', true)->with(['loyers' => function($query) {
            $query->where('statut', 'en_retard');
        }])->get(['id', 'nom', 'prenom', 'telephone']);

        return response()->json($locataires);
    }
}