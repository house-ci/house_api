<?php


namespace App\Helpers;


class Mappings
{
    const PlayersOrderByMapping = [
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'email' => 'email',
        'password' => 'password',
        'last_name' => 'nom',
        'first_name' => 'prenom',
        'birth_date' => 'date_naissance',
        'phone' => 'mobile',
        'id_type' => 'type_piece',
        'id_number' => 'num_piece',
        'status' => 'statut',
        'id' => 'id',
    ];

    const DefaultPlayersOrderByMapping = [
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'email' => 'email',
        'password' => 'password',
        'nom' => 'nom',
        'prenom' => 'prenom',
        'date_naissance' => 'date_naissance',
        'mobile' => 'mobile',
        'type_piece' => 'type_piece',
        'num_piece' => 'num_piece',
        'statut' => 'statut',
        'id' => 'id',
    ];

    const TicketsOrderByMapping = [
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'ticket_num' => 'num_ticket',
        'is_win' => 'is_win',
        'player_id' => 'id_joueur_fk',
        'id' => 'id',
    ];

    const DefaultTicketsOrderByMapping = [
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'num_ticket' => 'num_ticket',
        'is_win' => 'is_win',
        'id_joueur_fk' => 'id_joueur_fk',
        'id' => 'id',
    ];
}
