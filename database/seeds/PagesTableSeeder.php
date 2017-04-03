<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    public function run()
    {
        $page_repo = app(\App\Repositories\Page\PageRepositoryInterface::class);
        
        // we remove all the files in the config folder
        $files = glob(storage_path('app/pages/*'));
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }
        
        // we create the folder if it doesn't exist
        if (!is_dir($storage_path = storage_path('app/pages'))) {
            if (!is_dir($path = storage_path('app'))) {
                mkdir($path);
            }
            mkdir($path . '/pages');
        }
        
        $page_repo->createMultiple([
            [
                'slug'    => 'historique',
                'title'   => '<i class="fa fa-university"></i> Le club Université Nantes Aviron (UNA) en quelques dates',
                'content' => <<<EOT
## La naissance de l'ASU Nantes
Le club a été crée en 1985 à l'instigation de personnes persuadées de la nécessité d'offrir aux étudiants nantais leur club d'aviron. Auparavant, l'aviron universitaire fonctionnait au sein du Cercle de l'Aviron de Nantes dans le cadre d'une convention de partenariat entre ce club et l'Université de Nantes. Ses membres fondateurs, Claude Boumard et Lionel Girard, furent très vite rejoints par Guy Launay. Ils furent aidés dans leur tache par le Président de l'époque du CAN : Roald L'Hermitte. Le premier président du club fut l'un des vice-président de l'université : Philippe Hess.

## Le petit club sous tutelle devient un grand club indépendant
Pendant 2 ans l'"ASU Nantes" est resté hébergé par le Cercle Aviron de Nantes. Il ne pris son autonomie géographique qu'en 1987. Grâce au travail de Guy Launay, l'université de Nantes avait décidé de se donner les moyens d'un fonctionnement autonome en donnant à son club un terrain en bordure de l'Erdre en bas de la Faculté des Sciences. Les étudiants de cette époque se rappellent le côté pionnier des débuts. Défrichage, construction des pontons, montage d'une serre agricole en guise de hangar à bateaux, garages en tôle en guise de vestiaires etc… Pendant ce temps Guy Launay devenu Président, avec le soutien actif de Serge Renaudin, le Président de l'Université de Nantes d'alors, s'activait pour mener à bien la construction de ce qui allait devenir le premier club d'aviron construit au sein même d'une université française. Et le rêve se réalisa en 1990 avec l'inauguration des bâtiments actuels, soit un hangar de près de 1000m2, un bâtiment d'accueil avec des vestiaires et une salle d'honneur. Plus tard, en 1995 sont venus se rajouter un atelier, une salle de musculation, une salle d'ergomètre. Durant toute cette phase de genèse, les effectifs du club ne cesseront de croître. De 200 licenciés à sa création, ils passèrent à 450 en 1990, à 600 en 1997 pour atteindre plus de 700 en 1998, ce qui en faisait, à l'époque, le plus gros club en France en termes d'effectif.

## "ASU Nantes", puis "ASUNEC Aviron" et enfin, "Université Nantes Aviron"
Depuis sa création en 1985, le club "ASU NANTES", devenu en 1996 l'"ASUNEC Aviron", puis en 1999 l'"Université Nantes Aviron", a servi de modèle au développement de l'aviron universitaire en France. Sa renommée a vite dépassé les limites de l'hexagone, particulièrement grâce à sa grande régate internationale universitaire Les Régataïades Internationales de Nantes. L'UNA est aujourd'hui considéré comme le club de référence de l'aviron universitaire français, donnant par-là même une image très positive de l'Université et de la Ville de Nantes. Sa notoriété est aussi due à la succession de titres universitaires : plus de 30 acquis lors des championnats FFSU (Fédération Française des Sports Universitaires), réservées aux étudiants ainsi que par ses résultats lors des compétitions civiles FFA (Fédération Française d'Aviron).

## Une représentation grandissante dans le monde de la compétition
La première figure marquante du club fut sans nul doute Karen Botcherby, qui a représenté la France aux Universiaides de Zagreb en 1987. Elle y fut accompagnée par le barreur international Eric Lewandowski. Après cette époque dite des pionniers, vint la génération de Stéphane Hamon et de Sylvain Brunel tous deux internationaux, qui remportèrent les médailles d'argent en deux sans barreur et en deux de couple lors des championnats de France. Stéphane Hamon remportant pour sa part 2 autres médailles d'argent en skiff. En 1995, la première médaille d'or du club, celle du deux sans barreur sprint, fut remportée par Pascal Collet et Arnaud Picut. Cette performance fut ensuite confirmée par la médaille de bronze obtenue en quatre sans barreur lors des championnats sur 2000m. Picut et Collet faisaient alors équipage avec le toujours jeune Hamon et avec Erwan Launay. Il aura fallu attendre 1997 pour que le club inscrive à son palmarès sa première médaille féminine, celle de bronze, grâce au deux de couple composé de Claire Le Moal et de Corinne Le Moal. Cette dernière étant considérée comme la plus titrée des rameuses françaises avec 19 titres de championne de France (obtenus sous les couleurs du CNA Rouen). Enfin 1998 a permis à Anthony Cornet d'être sélectionné pour les championnats du monde de Cologne et pour les championnats du monde universitaires de Zagreb.

## L'UNA, un club sportif mais également une communauté associative et un état d'esprit
Il ne faut pas oublier que la vie de l'UNA, c'est aussi l'investissement de rameuses et de rameurs, sans qui le club n'aurait pas existé et ne continuerait pas à fonctionner. Qu'elles aient étées membres des instances dirigeantes ou simplement bénévoles, de nombreuses personnes ont marqué la vie du club Université Nantes Aviron (UNA) par leur passage sous les couleurs noires et blanches. C'est aujourd'hui un mélange d'anciennes et de nouvelles volontées qui s'emploient dans un seul objectif : porter le plus haut possible les couleurs du club Université Nantes Aviron !
EOT
                ,
                'meta_title' => '',
                'active'  => true,
            ],
            [
                'slug'    => 'Statuts',
                'title'   => '<i class="fa fa-compass"></i> Les statuts du club Université Nantes Aviron (UNA)',
                'content' => <<<EOT
*La lecture du règlement intérieur est obligatoire pour tous les membres de l'association Université Nantes Aviron. Tout non-respect pourra entraîner des sanctions allant jusqu'à l'exclusion du club.*  
    
## Article 1 - Objet

Les dispositions du présent règlement constituent le règlement intérieur général applicable à toute personne pénétrant dans le club Université Nantes Aviron.

Les consignes que vous trouverez dans ce règlement intérieur sont destinées à améliorer la sécurité et le fonctionnement du club d'aviron.

Dans un intérêt commun, nous vous demandons de les respecter scrupuleusement afin que les activités puissent se dérouler dans les meilleures conditions.
Les usagers spécifiques, tels que les associations sportives ou établissement scolaires, devront outre le règlement intérieur, se conforter également aux conventions particulières les concernant.

Les activités du club d'aviron sont de caractères éducatif et sportif. L'enseignement progressif des activités nautiques implique le suivi des programmes d'activité.

Les tâches courantes de rangement et de nettoyage du matériel font partir des activités et nul ne peut en être dispensé.

## Article 2 - Horaires d'ouverture
Les jours et horaires d'ouvertures sont fixés en début d'année scolaire par le Bureau Directeur et affichés à l'entré de l'établissement.

Le club d'aviron pourra être fermé exceptionnellement pour les motifs suivants : travaux, hygiène et sécurité, jours fériés, manifestation exceptionnelle, formation du personnel.

Lorsqu'elles sont prévisibles, les dates de fermeture exceptionnelles sont affichées à l'avance, à l'entrée du club d'aviron et sur son site Internet. Lorsqu'elles ne peuvent pas être programmées à l'avance, elles sont annoncées, dès que possible, par voie de presse et d'affichage à l'entrée de l'établissement. Le Bureau Directeur, ainsi que le responsable sportif du club d'aviron ou son représentant, peuvent coordonner la fermeture de l'établissement sans préavis pour tout motif rendant cette fermeture impérative.

## Article 3 - Conditions d'inscription
Pour accéder aux activités proposées par le club d'aviron, toute personne devra s'être préalablement inscrite auprès des services administratifs.

L'inscription est formalisée par le participant ou son représentant légal par la signature de la fiche d'inscription et le règlement de la cotisation.

### Dossiers administratifs :
Le participant ou son son représentant légal doit présenter un certificat médical l'autorisant à la pratique de l'aviron et une attestation d'aptitude à la natation.

Les conditions de paiement ainsi que les tarifs sont fixés chaque année par le Bureau Directeur. Ces informations sont affichés à l'entrée de l'établissement.

Les membres d'un groupe constitué (scolaires, associations sportives, ...) doivent satisfaire aux même condition que celles ci-dessus, attestées par le responsable du groupe lors de la signature de la convention d'utilisation du club d'aviron.

## Article 4 - Sécurité
La décision d'annulation pour raison climatique (température, vent, précipitations, ...) ou pour toutes autres raisons d'hygiène ou de sécurité, pourra être ordonnée par le responsable sportif d'aviron ou son représentant.

Il est strictement interdit :  
- d'embarquer sans l'autorisation du personnel d'encadrement
- de s'éloigner des zones d'évolution prescrites
- de stationner dans les chenaux balisés réservés aux embarcations d'un autre type que celles sur lesquelles on évolue
- de se baigner volontairement, de provoquer un chavirage
- de fumer dans les locaux et sur les bateaux
- d'être pieds nus dans le périmètre du club d'aviron
- de provoquer volontairement une collision avec une autre embarcation

## Article 5 - Matériel
Le matériel confié aux usagers est conforme à la législation en vigueur et est en bon état de fonctionnement et être utilisé dans le cadre de son utilisation normale.

Durant les activités, il est placé sous la responsabilité de l'usager.

Il doit être manipulé avec soins, inspecté et rangé à chaque fin de séance.

Toutes les avaries constatées doivent être signalées au personnel d'encadrement ou de maintenance du club d'aviron.

## Article 6 - Responsabilités
Tous les effets personnels doivent être déposés dans les vestiaires prévus à cet usage.

Le club Université Nantes Aviron décline toute responsabilité pour les objets perdus ou volés dans l'établissement.

Les objets trouvés sont à déposer à l'accueil à remettre au personnel de l'UNA.

## Article 7 - Sens civique
Afin que les activités du club Université Nantes Aviron puissent se dérouler en harmonie avec celles des autres utilisateurs du plan d'eau, les ,usagers doivent un comportement décent et emprunt de courtoisie lors des séances de navigation ou à terre.

Les visiteurs ou usagers ayant une attitude incorrecte ou offensant les moeurs ou incompatible avec le bon fonctionnement du club université Nantes Aviron seront immédiatement invités à quitter l'établissement par le personnel ou la force publique, sans remboursement, et ils pourront, à l'avenir, se voir interdire l'entrée de l'établissement.

## Article 8 - Matériel roulant
Les véhicules sont réservés aux déplacements du club, et conduit par un membre licencié du club. Tous les documents relatif aux véhicules doivent remplis correctement.

Seul le Comité Directeur de l'UNA pourra répondre aux demandes de prêts pour des utilisateurs non licenciés du club.

## Article 9 - Dispositions antérieures
Toutes les dispositions antérieures, contraires au présent règlement sont abrogées.
EOT
            ,
                'active'  => true,
            ],
            [
                'slug'    => 'reglement-interieur',
                'title'   => '<i class="fa fa-gavel"></i> Le règlement intérieur du club Université Nantes Aviron (UNA)',
                'content' => <<<EOT
## A/ Objet et composition de l'association

### Article 1
L’association dite « Université Nantes Aviron » est fondée en 1999. Elle a pour objet la pratique de l’aviron sous toutes ses formes et de représenter l’Université de Nantes dans les épreuves sportives où celle-ci est appelée à participer. Ex : Championnats de France FFSA et FFSU, rencontres internationales civiles et universitaires, régates civiles et universitaires etc.

### Article 2
Elle a son siège social à Nantes : Club Université Nantes Aviron, 2 rue de la Houssinière, 44300 Nantes.  
Elle a été déclarée à la préfecture de LOIRE ATLANTIQUE sous le n°0442025654 le 27 octobre 1999. Journal officiel du : 4 décembre 1999.

### Article 3
L'Association de compose :  
- de membres actifs  
- de membres bénévoles accompagnant  
- de membres honoraires  
- de membres d’honneur  
- de membres bienfaiteurs  

Sont membres actifs :  
- les étudiants de l’Université de Nantes et de l’enseignement supérieur  
- les personnels de l’Université de Nantes ou de l’enseignement supérieur  
- toute autre personne ne rentrant pas dans les deux premières catégories sous réserve que leur nombre ne dépasse pas 30% de l’effectif total  
 
Toutes ces personnes doivent être licenciées et à jour de leur cotisation.

Sont membres bénévoles accompagnant :  
- les personnes, notamment les parents qui utilisent leur véhicule personnel pour les besoins de l’UNA seront, après confirmation du bureau, admis membres accompagnant de l’association. Cette qualification leur permettra d’obtenir des crédits d’impôts dont la valeur s’établit actuellement à 66% des frais engagés selon un barème communiqué chaque année par le Ministère des Finances. Il ne leur sera pas attribué  de licence de rameur  

Sont membres honoraires :  
- les personnes physiques ou morales qui soutiennent l’association en acquittant une cotisation fixée par le Comité Directeur  

Sont membres d’honneur :  
- les personnes physiques et morales auxquelles ce titre est décerné par le Comité Directeur en raison des services rendus ou qu’elles rendent. Ce titre confère le droit de participer à l’Assemblé Générale annuelle sans être tenu d’avoir a acquitter la cotisation annuelle

Sont membres bienfaiteurs :  
- les personnes physiques et morales ne répondant pas aux critères de membres actifs mais qui soutiennent l’association en lui versant une cotisation annuelle dont le montant est fixé chaque année par le Comité Directeur

### Article 4
La qualité se perd :  
- par la démission adressée par écrit au Président  
- par la radiation prononcée pour non-paiement de la cotisation ou pour motifs graves par le Comité Directeur, le membre intéressé ayant été préalablement appelé à fournir des explications
                                    
### Article 5
L’association est affiliée à la FFA (Fédération Française d’Aviron) par l’intermédiaire de la ligue régionale des pays de la Loire d’aviron. Elle s’engage à se conformer entièrement aux règles et règlements établis par la FFSA. Elle s’engage à se soumettre aux sanctions disciplinaires qui lui seraient infligées par l’application des dits règlements.

L’association est affiliée à la FFSU (Fédération Française du Sport Universitaire) par l’intermédiaire du Comité Régional des pays de la Loire. Elle s’engage à se conformer entièrement aux règles et règlements établis par la FFSU. Elle s’engage à se soumettre aux sanctions disciplinaires qui lui seraient infligées par l’application des dits règlements.

L’association s’engage à assurer la liberté d’opinion et à respecter les droits de la défense, et particulier des membres faisant l’objet d’une mesure de radiation ou d’exclusion.

L’association s’engage à garantir le fonctionnement démocratique ainsi que la transparence de sa gestion.

L’association s’interdit toute discrimination illégale, en permettant plus particulièrement l’égal accès des femmes et des hommes aux instances dirigeantes de l’association, et veille au respect des règles déontologiques du sport qui sont définis par le Comité National olympique et Sportif Français (CNOSF).

L’association s’engage à faire respecter les règles d’encadrement, d’hygiène et de sécurité applicables aux disciplines pratiquées par ses membres.

## B/ Administration et fonctionnement

### Article 6
L’association est administrée par un Comité Directeur composé de membres de droit et de membres élus lors de l’Assemblé Générale. Le Président fixe l’ordre du jour du Comité Directeur. Les délibérations sont acquises à la majorité relative des membres présents ou représentés. Pour toutes les délibérations, les votes ont lieu à bulletin secret, sur la demande d’au moins un membre du Comité Directeur, ou ouvert après mise aux voix par le Président. Le vote par procuration est admis à raison d’un mandat par personne désignée.

Sont membres de droit :  
- le président de l’Université de Nantes ou son représentant
- le directeur du SUAPS de l’Université de Nantes
                                    
Peut être élu :  
- tout licencié depuis plus de 3 mois à jour de sa cotisation.

### Article 7
L’Assemblé Générale se réunit chaque année lors du 4ème trimestre de l’année civile. Elle peut être convoquée extraordinairement, soit par le Comité Directeur, soit à la demande du quart de ses membres actifs. La date de l’Assemblée Générale est publiée au moins quinze jours avant sa réunion.

L’ordre du jour est fixé par le Comité Directeur. L’Assemblée Générale délibère sur les questions mises à l’ordre du jour notamment sur les rapports relatifs à la gestion et à l’activité du Comité Directeur, à la situation morale et financière de l’association.

Elle approuve les comptes de l’exercice clos après rapport du Commissaire aux comptes et vote le budget.

Elle donne quitus aux administrateurs et procède aux élections du Comité Directeur et du commissaire aux comptes qui ne peut être membre du dit Comité Directeur.

Lors de l’Assemblée Générale, les membres étudiants élisent leur Président qui devient par statut Vice Président de l’association.

### Article 8
Le Comité Directeur, organe exécutif de l’association administre l’association pour une année. Il est composé de 24 membres dont au minimum douze étudiants. Il est renouvelable tous les ans.
                                    
Le Comité Directeur élit un bureau qui comprend :
- un Président obligatoirement personnel de l’Université de Nantes
- deux Vice-Présidents dont le Président des étudiants
- un Trésorier et Trésorier adjoint
- un Secrétaire et Secrétaire adjoint
- deux membres

### Article 9
Le Comité Directeur se réunit en séance ordinaire au moins une fois par trimestre sur convocation du bureau : le Président en fixe l’ordre du jour. Le Comité Directeur se réunit en séance extraordinaire à la demande du quart de ses membres. En cas d’égalité de vote la voix du Président est prépondérante. Le Bureau se réunit une fois par mois en dehors des réunions du  Comité Directeur sur convocation du Président.

### Article 10
Le Comité Directeur est investi par l’Assemblée Générale des pouvoirs les plus étendus pour l’organisation, la gestion et l’administration de l’association.

### Article 11
Le Comité Directeur applique les décisions de l’Assemblée Générale et agrée les taux des cotisations. Il ordonne les dépenses avec délégation de pouvoir au Trésorier Général. Il décerne les titres de membres d’honneur et de membres bienfaiteurs.

### Article 12
Il est tenu procès-verbal de toutes les séances et Assemblées de l’association par le Secrétaire. Les procès-verbaux sont signés et transcrits sur un livre coté et paraphé tenu à cet effet au secrétariat de l’association.

## C/ Ressources

### Article 13
Les ressources de l’association se composent :  
- des cotisations de ses membres dont le montant est ratifié par l’Assemblée Générale
- de subventions qui peuvent lui être accordées  
- des intérêts et revenus de biens et valeurs qu’elle pourrait posséder et de toutes autres ressources légalement reconnues et compatibles avec sa capacité civile  
- de dons manuels  

Ces ressources servent uniquement à pouvoir au bon fonctionnement de l’ensemble de ses activités.

### Article 14
Il est tenu à jour une comptabilité des recettes et des dépenses avec les journaux annexes. Le budget annuel de la section et le bilan de l’exercice sont arrêtés au 30 septembre de chaque année.

## D/ Modification & dissolution

### Article 15
Seule l’Assemblée Générale extraordinaire peut prononcer des modifications de ces statuts ou la dissolution de l’association. Elle doit être composée de la moitié au moins des membres ayant droit d’en faire partie et ses délibérations doivent être prises à la majorité des 2/3 des membres présents ou représentés à raison d’un mandat par personne.
																		
Si l’Assemblée Générale extraordinaire n’a pu recueillir le nombre suffisant de membres sur une première convocation, il est convoqué une seconde Assemblée Générale extraordinaire qui délibère valablement, sans condition de quorum, à la majorité simple.

### Article 16
En cas de dissolution de l’association, son patrimoine devient automatiquement propriété de l’Université de Nantes.
EOT
                ,
                'active'  => true,
            ],
        ]);
        
        // term and conditions page
        // app
        $app_url = route('home');
        // app owner
        $app_owner = config('settings.app_name_' . config('app.locale'));
        $owner_company_status = 'Association Loi 1901';
        $owner_address = config('settings.address') . ' ' . config('settings.zip_code') . ' ' . config('settings.city') . ' - ' . config('settings.country');
        // app creator
        $app_developer = 'Arthur LORENT';
        $app_developer_website = 'http://www.doyoubuzz.com/arthur-lorent';
        // app publication direction
        $publication_director = $app_owner;
        $publication_director_contact = '[' . config('settings.contact_email') . '](mailto:' . config('settings.contact_email') . ' "' . config('settings.contact_email') . '")';
        // app host
        $app_host = 'OVH';
        $app_host_website = 'https://www.ovh.com';
        // page creation
        $page_repo->create([
            'slug'    => 'mentions-legales',
            'title'   => 'Mentions légales',
            'content' => <<<EOT
## 1. Présentation du site.

En vertu de l'article 6 de la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique, il est précisé aux utilisateurs du site [$app_url]($app_url "$app_owner") l'identité des différents intervenants dans le cadre de sa réalisation et de son suivi :  
- **Propriétaire :** $app_owner – $owner_company_status – $owner_address  
- **Conception et développement :** [$app_developer]($app_developer_website "$app_developer")  
- **Responsable publication :** $publication_director – $publication_director_contact - Le responsable publication est une personne physique ou une personne morale.  
- **Maintenance :** [$app_developer]($app_developer_website "$app_developer")  
- **Hébergeur :** $app_host – $app_host_website  

## 2. Conditions générales d’utilisation du site et des services proposés.

L’utilisation du site [$app_url]($app_url "$app_owner") implique l’acceptation pleine et entière des conditions générales d’utilisation ci-après décrites. Ces conditions d’utilisation sont susceptibles d’être modifiées ou complétées à tout moment, les utilisateurs du site [$app_url]($app_url "$app_owner") sont donc invités à les consulter de manière régulière.

Ce site est normalement accessible à tout moment aux utilisateurs. Une interruption pour raison de maintenance technique peut être toutefois être décidée par $app_owner, qui s’efforcera alors de communiquer préalablement aux utilisateurs les dates et heures de l’intervention.

Le site [$app_url]($app_url "$app_owner") est mis à jour régulièrement par $publication_director. De la même façon, les mentions légales peuvent être modifiées à tout moment : elles s’imposent néanmoins à l’utilisateur qui est invité à s’y référer le plus souvent possible afin d’en prendre connaissance.  

## 3. Description des services fournis.

Le site [$app_url]($app_url "$app_owner") a pour objet de fournir une information concernant l’ensemble des activités de la société.

$app_owner s’efforce de fournir sur le site [$app_url]($app_url "$app_owner") des informations aussi précises que possible. Toutefois, il ne pourra être tenu responsable des omissions, des inexactitudes et des carences dans la mise à jour, qu’elles soient de son fait ou du fait des tiers partenaires qui lui fournissent ces informations.

Tous les informations indiquées sur le site [$app_url]($app_url "$app_owner") sont données à titre indicatif, et sont susceptibles d’évoluer. Par ailleurs, les renseignements figurant sur le site [$app_url]($app_url "$app_owner") ne sont pas exhaustifs. Ils sont donnés sous réserve de modifications ayant été apportées depuis leur mise en ligne.

## 4. Limitations contractuelles sur les données techniques.

Le site utilise la technologie JavaScript.

Le site Internet ne pourra être tenu responsable de dommages matériels liés à l’utilisation du site. De plus, l’utilisateur du site s’engage à accéder au site en utilisant un matériel récent, ne contenant pas de virus et avec un navigateur de dernière génération mis-à-jour.

## 5. Propriété intellectuelle et contrefaçons.

$app_owner est propriétaire des droits de propriété intellectuelle ou détient les droits d’usage sur tous les éléments accessibles sur le site, notamment les textes, images, graphismes, logo, icônes, sons, logiciels.

Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable de : $app_owner.

Toute exploitation non autorisée du site ou de l’un des éléments qu’il contient sera considérée comme constitutive d’une contrefaçon et poursuivie conformément aux dispositions des articles L.335-2 et suivants du Code de Propriété Intellectuelle.

## 6. Limitations de responsabilité.

$app_owner ne pourra être tenu responsable des dommages directs et indirects causés au matériel de l’utilisateur, lors de l’accès au site [$app_url]($app_url "$app_owner"), et résultant soit de l’utilisation d’un matériel ne répondant pas aux spécifications indiquées au point 4, soit de l’apparition d’un bug ou d’une incompatibilité.

$app_owner ne pourra également être tenu responsable des dommages indirects (tels par exemple qu’une perte de marché ou perte d’une chance) consécutifs à l’utilisation du site [$app_url]($app_url "$app_owner").

Des espaces interactifs (possibilité de rédiger des commentaire des actualités, par exemple) sont à la disposition des utilisateurs. $app_owner se réserve le droit de supprimer, sans mise en demeure préalable, tout contenu déposé dans cet espace qui contreviendrait à la législation applicable en France, en particulier aux dispositions relatives à la protection des données. Le cas échéant, $app_owner se réserve également la possibilité de mettre en cause la responsabilité civile et/ou pénale de l’utilisateur, notamment en cas de message à caractère raciste, injurieux, diffamant, ou pornographique, quel que soit le support utilisé (texte, photographie…).

## 7. Gestion des données personnelles.

En France, les données personnelles sont notamment protégées par la loi n° 78-87 du 6 janvier 1978, la loi n° 2004-801 du 6 août 2004, l'article L. 226-13 du Code pénal et la Directive Européenne du 24 octobre 1995.

A l'occasion de l'utilisation du site [$app_url]($app_url "$app_owner"), peuvent êtres recueillies : l'URL des liens par l'intermédiaire desquels l'utilisateur a accédé au site [$app_url]($app_url "$app_owner"), le fournisseur d'accès de l'utilisateur, l'adresse de protocole Internet (IP) de l'utilisateur.

En tout état de cause $app_owner ne collecte des informations personnelles relatives à l'utilisateur que pour le besoin de certains services proposés par le site [$app_url]($app_url "$app_owner"). L'utilisateur fournit ces informations en toute connaissance de cause, notamment lorsqu'il procède par lui-même à leur saisie. Il est alors précisé à l'utilisateur du site [$app_url]($app_url "$app_owner") l’obligation ou non de fournir ces informations.

Conformément aux dispositions des articles 38 et suivants de la loi 78-17 du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés, tout utilisateur dispose d’un droit d’accès, de rectification et d’opposition aux données personnelles le concernant, en effectuant sa demande écrite et signée, accompagnée d’une copie du titre d’identité avec signature du titulaire de la pièce, en précisant l’adresse à laquelle la réponse doit être envoyée.

Aucune information personnelle de l'utilisateur du site [$app_url]($app_url "$app_owner") n'est publiée à l'insu de l'utilisateur, échangée, transférée, cédée ou vendue sur un support quelconque à des tiers. Seule l'hypothèse du rachat de $app_owner et de ses droits permettrait la transmission des dites informations à l'éventuel acquéreur qui serait à son tour tenu de la même obligation de conservation et de modification des données vis à vis de l'utilisateur du site [$app_url]($app_url "$app_owner").

Le site n'est pas déclaré à la CNIL car il ne recueille pas d'informations personnelles.

Les bases de données sont protégées par les dispositions de la loi du 1er juillet 1998 transposant la directive 96/9 du 11 mars 1996 relative à la protection juridique des bases de données.

## 8. Liens hypertextes et cookies.

Le site [$app_url]($app_url "$app_owner") contient un certain nombre de liens hypertextes vers d’autres sites, mis en place avec l’autorisation de $app_owner. Cependant, $app_owner n’a pas la possibilité de vérifier le contenu des sites ainsi visités, et n’assumera en conséquence aucune responsabilité de ce fait.

La navigation sur le site [$app_url]($app_url "$app_owner") est susceptible de provoquer l’installation de cookie(s) sur l’ordinateur de l’utilisateur. Un cookie est un fichier de petite taille, qui ne permet pas l’identification de l’utilisateur, mais qui enregistre des informations relatives à la navigation d’un ordinateur sur un site. Les données ainsi obtenues visent à faciliter la navigation ultérieure sur le site, et ont également vocation à permettre diverses mesures de fréquentation.

Le refus d’installation d’un cookie peut entraîner l’impossibilité d’accéder à certains services. L’utilisateur peut toutefois configurer son ordinateur pour refuser l’installation des cookies : [supprimer ses cookies, mode d'emploi](http://www.linternaute.com/hightech/encyclo-pratique/internet/divers/4450/supprimer-les-cookies-mode-d-emploi.html "supprimer ses cookies, mode d'emploi")

## 9. Droit applicable et attribution de juridiction.

Tout litige en relation avec l’utilisation du site [$app_url]($app_url "$app_owner") est soumis au droit français. Il est fait attribution exclusive de juridiction aux tribunaux compétents de Paris.

## 10. Les principales lois concernées.

- Loi n° 78-17 du 6 janvier 1978, notamment modifiée par la loi n° 2004-801 du 6 août 2004 relative à l'informatique, aux fichiers et aux libertés.  
- Loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique.

## 11. Lexique.

- **Utilisateur :** Internaute se connectant, utilisant le site susnommé.  
- **Informations personnelles :** « les informations qui permettent, sous quelque forme que ce soit, directement ou non, l'identification des personnes physiques auxquelles elles s'appliquent » (article 4 de la loi n° 78-17 du 6 janvier 1978).
EOT
            ,
            'active'  => true,
        ]);
    }
}
