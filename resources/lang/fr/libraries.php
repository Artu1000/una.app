<?php

return [
    
    // images
    "images" => [
        
        "page"    => [
            "title"  => [
                "management" => "Gestion des images",
                "upload"     => "Charger des images",
                "list"       => "Liste des images",
                "drop"       => "Glissez vos images ou cliquez ici pour les sélectionner.",
            ],
            "action" => [
                "delete" => "Supprimer l'image",
                "start"  => "Lancer le chargement des images",
                "remove" => "Supprimer toutes les images de la liste",
            ],
            "info"   => [
                "ext" => "Types d'images acceptées : :ext.",
            ],
            "label"  => [
                "image"             => "Image",
                "src"               => "Nom",
                "alias"             => "Alias",
                "alias_placeholder" => "Définissez un alias",
                "created_at"        => "Date création",
            ],
        ],
        
        // messages
        "message" => [
            "create" => [
                "success" => "L'image <b>:image</b> a bien été créée.",
                "failure" => "Une erreur est survenue lors du chargement de l'image \":image\".",
            ],
            "find"   => [
                "failure" => "L'image <b>#:id</b> n'existe pas.",
            ],
            "update" => [
                "success" => "L'image <b>:image</b> a bien été mise à jour.",
                "failure" => "Une erreur est survenue lors de la mise à jour de l'image <b>:image</b>.",
            ],
            "delete" => [
                "success" => "L'image <b>:image</b> a bien été supprimée.",
                "failure" => "Une erreur est survenue lors de la suppression de l'image <b>:image</b>.",
            ],
            "file"   => [
                "type"    => "Format d'image incorrect.",
                "size"    => "L'image est trop grande ({{filesize}}Mo). Taille maximale : {{maxFilesize}}Mo ",
                "success" => [
                    "congratulations" => "Félicitations",
                    "message"         => "<b>:count</b> image a été ajoutée|Félicitations : <b>:count</b> images ont été ajoutées",
                ],
                "error"   => [
                    "however" => "cependant",
                    "beware"  => "Attention",
                    "title"   => "<b>:count</b> erreur a été détectée :|<b>:count</b> erreurs ont été détectées :",
                    "detail"  => "<b>:name</b> : :error",
                ],
            ],
        ],
    ],
    
    // files
    "files"  => [
        
        "page"    => [
            "title"  => [
                "management" => "Gestion des fichiers",
                "upload"     => "Charger des fichiers",
                "list"       => "Liste des fichiers",
                "drop"       => "Glissez vos fichiers ou cliquez ici pour les sélectionner.",
            ],
            "action" => [
                "delete" => "Supprimer le fichier",
                "start"  => "Lancer le chargement des fichiers",
                "remove" => "Supprimer tous les fichiers de la liste",
            ],
            "info"   => [
                "ext" => "Types de fichiers acceptés : :ext.",
            ],
            "label"  => [
                "file"              => "Fichier",
                "src"               => "Nom",
                "alias"             => "Alias",
                "alias_placeholder" => "Définissez un alias",
                "created_at"        => "Date création",
            ],
        ],
        
        // messages
        "message" => [
            "create" => [
                "success" => "Le fichier <b>:file</b> a bien été créé.",
                "failure" => "Une erreur est survenue lors du chargement du fichier \":file\".",
            ],
            "find"   => [
                "failure" => "Le fichier <b>#:id</b> n'existe pas.",
            ],
            "update" => [
                "success" => "Le fichier <b>:file</b> a bien été mis à jour.",
                "failure" => "Une erreur est survenue lors de la mise à jour du fichier <b>:fichier</b>.",
            ],
            "delete" => [
                "success" => "Le fichier <b>:file</b> a bien été supprimé.",
                "failure" => "Une erreur est survenue lors de la suppression du fichier <b>:fichier</b>.",
            ],
            "file"   => [
                "type"    => "Format de fichier incorrect.",
                "size"    => "Le fichier est trop grand ({{filesize}}Mo). Taille maximale : {{maxFilesize}}Mo ",
                "success" => [
                    "congratulations" => "Félicitations",
                    "message"         => "<b>:count</b> fichier a été ajouté|Félicitations : <b>:count</b> fichiers ont été ajoutés",
                ],
                "error"   => [
                    "however" => "cependant",
                    "beware"  => "Attention",
                    "title"   => "<b>:count</b> erreur a été détectée :|<b>:count</b> erreurs ont été détectées :",
                    "detail"  => "<b>:name</b> : :error",
                ],
            ],
        ],
    ],
];