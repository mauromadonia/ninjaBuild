<?php
// Questa interfaccia rappresenta i metodi da utilizzare nella classe Routes specifica del sito web.
namespace Ninja;

interface Routes
{
    // Rotte delle pagine necessarie al funzionamento
    public function getRoutes(): array; 
    // Classe di autenticazione degli utenti - commentare se non si vuole utenza.
    public function getAuthentication(): \Ninja\Authentication;  
}
