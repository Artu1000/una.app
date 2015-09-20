<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    public function run()
    {
        $page_repo = App::make(App\Repositories\Page\PageRepositoryInterface::class);

        $page_repo->createMultiple([
            [
                'key' => 'historique',
                'title' => '<i class="fa fa-university"></i> Le club Université Nantes Aviron (UNA) en quelques dates ...',
                'image' => '',
                'content' => "<h3>La naissance de l'ASU Nantes</h3>
                                <p>Le club a été crée en 1985 à l'instigation de personnes persuadées de la nécessité d'offrir aux étudiants nantais leur club d'aviron. Auparavant, l'aviron universitaire fonctionnait au sein du Cercle de l'Aviron de Nantes dans le cadre d'une convention de partenariat entre ce club et l'Université de Nantes. Ses membres fondateurs, Claude Boumard et Lionel Girard, furent très vite rejoints par Guy Launay. Ils furent aidés dans leur tache par le Président de l'époque du CAN : Roald L'Hermitte. Le premier président du club fut l'un des vice-président de l'université : Philippe Hess.</p>
                                <h3>Le petit club sous tutelle devient un grand club indépendant</h3>
                                <p>Pendant 2 ans l'\"ASU Nantes\" est resté hébergé par le Cercle Aviron de Nantes. Il ne pris son autonomie géographique qu'en 1987. Grâce au travail de Guy Launay, l'université de Nantes avait décidé de se donner les moyens d'un fonctionnement autonome en donnant à son club un terrain en bordure de l'Erdre en bas de la Faculté des Sciences. Les étudiants de cette époque se rappellent le côté pionnier des débuts. Défrichage, construction des pontons, montage d'une serre agricole en guise de hangar à bateaux, garages en tôle en guise de vestiaires etc… Pendant ce temps Guy Launay devenu Président, avec le soutien actif de Serge Renaudin, le Président de l'Université de Nantes d'alors, s'activait pour mener à bien la construction de ce qui allait devenir le premier club d'aviron construit au sein même d'une université française. Et le rêve se réalisa en 1990 avec l'inauguration des bâtiments actuels, soit un hangar de près de 1000m2, un bâtiment d'accueil avec des vestiaires et une salle d'honneur. Plus tard, en 1995 sont venus se rajouter un atelier, une salle de musculation, une salle d'ergomètre. Durant toute cette phase de genèse, les effectifs du club ne cesseront de croître. De 200 licenciés à sa création, ils passèrent à 450 en 1990, à 600 en 1997 pour atteindre plus de 700 en 1998, ce qui en faisait, à l'époque, le plus gros club en France en termes d'effectif.</p>
                                <h3>\"ASU Nantes\", puis \"ASUNEC Aviron\" et enfin, \"Université Nantes Aviron\"</h3>
                                <p>Depuis sa création en 1985, le club \"ASU NANTES\", devenu en 1996 l'\"ASUNEC Aviron\", puis en 1999 l'\"Université Nantes Aviron\", a servi de modèle au développement de l'aviron universitaire en France. Sa renommée a vite dépassé les limites de l'hexagone, particulièrement grâce à sa grande régate internationale universitaire Les Régataïades Internationales de Nantes. L'UNA est aujourd'hui considéré comme le club de référence de l'aviron universitaire français, donnant par-là même une image très positive de l'Université et de la Ville de Nantes. Sa notoriété est aussi due à la succession de titres universitaires : plus de 30 acquis lors des championnats FFSU (Fédération Française des Sports Universitaires), réservées aux étudiants ainsi que par ses résultats lors des compétitions civiles FFA (Fédération Française d'Aviron).</p>
                                <h3>Une représentation grandissante dans le monde de la compétition</h3>
                                <p>La première figure marquante du club fut sans nul doute Karen Botcherby, qui a représenté la France aux Universiaides de Zagreb en 1987. Elle y fut accompagnée par le barreur international Eric Lewandowski. Après cette époque dite des pionniers, vint la génération de Stéphane Hamon et de Sylvain Brunel tous deux internationaux, qui remportèrent les médailles d'argent en deux sans barreur et en deux de couple lors des championnats de France. Stéphane Hamon remportant pour sa part 2 autres médailles d'argent en skiff. En 1995, la première médaille d'or du club, celle du deux sans barreur sprint, fut remportée par Pascal Collet et Arnaud Picut. Cette performance fut ensuite confirmée par la médaille de bronze obtenue en quatre sans barreur lors des championnats sur 2000m. Picut et Collet faisaient alors équipage avec le toujours jeune Hamon et avec Erwan Launay. Il aura fallu attendre 1997 pour que le club inscrive à son palmarès sa première médaille féminine, celle de bronze, grâce au deux de couple composé de Claire Le Moal et de Corinne Le Moal. Cette dernière étant considérée comme la plus titrée des rameuses françaises avec 19 titres de championne de France (obtenus sous les couleurs du CNA Rouen). Enfin 1998 a permis à Anthony Cornet d'être sélectionné pour les championnats du monde de Cologne et pour les championnats du monde universitaires de Zagreb.</p>
                                <h3>L'UNA, un club sportif mais également une communauté et un état d'esprit</h3>
                                <p>Mais il ne faut pas oublier que la vie d'un club ne se fait pas uniquement avec son palmarès. L'Université Nantes Aviron, c'est aussi l'investissement de rameuses et rameurs sans qui le club n'aurait pas existé et ne continuerait pas à fonctionner et qui ont marqué leur passage sous les couleurs noires et blanches. Comme par exemple Benoît, Thierry PROTT alias \"Patinette\", Marc et Françoise, Antoine dit \"Tonio\" et son compère Ronan, les rois du bar Steve, Céline et Erwan, Christophe le maître de cérémonie de toutes les mémorables soirées du club, sans oublier les deux anciens : papy Marcel et papy Jean. Tous ont montré le chemin que se sont empressés de suivre toute l'équipe actuelle. Et dans quelques années d'autres générations suivront avec une seule volonté : Porter le plus haut possible les couleurs du club Université Nantes Aviron !</p>",
                'meta_title' => 'Historique',
                'meta_description' => '',
                'meta_keywords' => 'club, université, nantes, aviron, historique, histoire'
            ],
            [
                'key' => 'statuts',
                'title' => '<i class="fa fa-compass"></i> Les statuts du club Université Nantes Aviron (UNA)',
                'image' => '',
                'content' => "<h3>A/ Objet et composition de l'association</h3>

                                    <h4>Article 1</h4>
                                    <p>L’association dite « Université Nantes Aviron » est fondée en 1999. Elle a pour objet la pratique de l’aviron sous toutes ses formes et de représenter l’Université de Nantes dans les épreuves sportives où celle-ci est appelée à participer. Ex : Championnats de France FFSA et FFSU, rencontres internationales civiles et universitaires, régates civiles et universitaires etc.</p>

                                    <h4>Article 2</h4>
                                    <p>Elle a son siège social à Nantes : Club Université Nantes Aviron, 2 rue de la Houssinière, 44300 Nantes.
                                    Elle a été déclarée à la préfecture de LOIRE ATLANTIQUE sous le n°0442025654 le 27 octobre 1999. Journal officiel du : 4 décembre 1999.</p>

                                    <h4>Article 3</h4>
                                    <ul>
                                    <p>L’Association de compose :</p>
                                    <li>de membres actifs</li>
                                    <li>de membres bénévoles accompagnant</li>
                                    <li>de membres honoraires</li>
                                    <li>de membres d’honneur</li>
                                    <li>de membres bienfaiteurs</li>
                                    </ul>
                                    <ul>
                                    <p>Sont membres actifs :</p>
                                    <li>les étudiants de l’Université de Nantes et de l’enseignement supérieur</li>
                                    <li>les personnels de l’Université de Nantes ou de l’enseignement supérieur</li>
                                    <li>toute autre personne ne rentrant pas dans les deux premières catégories sous réserve que leur nombre ne dépasse pas 30% de l’effectif total</li>
                                    Toutes ces personnes doivent être licenciées et à jour de leur cotisation.
                                    </ul>
                                    <ul>
                                    <p>Sont membres bénévoles accompagnant :</p>
                                    <li>les personnes, notamment les parents qui utilisent leur véhicule personnel pour les besoins de l’UNA seront, après confirmation du bureau, admis membres accompagnant de l’association. Cette qualification leur permettra d’obtenir des crédits d’impôts dont la valeur s’établit actuellement à 66% des frais engagés selon un barème communiqué chaque année par le Ministère des Finances. Il ne leur sera pas attribué  de licence de rameur</li>
                                    </ul>
                                    <ul>
                                    <p>Sont membres honoraires :</p>
                                    <li>les personnes physiques ou morales qui soutiennent l’association en acquittant une cotisation fixée par le Comité Directeur</li>
                                    </ul>
                                    <ul>
                                    <p>Sont membres d’honneur :</p>
                                    <li>les personnes physiques et morales auxquelles ce titre est décerné par le Comité Directeur en raison des services rendus ou qu’elles rendent. Ce titre confère le droit de participer à l’Assemblé Générale annuelle sans être tenu d’avoir a acquitter la cotisation annuelle</li>
                                    </ul>
                                    <ul>
                                    <p>Sont membres bienfaiteurs :</p>
                                    <li>les personnes physiques et morales ne répondant pas aux critères de membres actifs mais qui soutiennent l’association en lui versant une cotisation annuelle dont le montant est fixé chaque année par le Comité Directeur</li>
                                    </ul>

                                    <h4>Article 4</h4>
                                    <ul>
                                    <p>La qualité se perd :</p>
                                    <li>par la démission adressée par écrit au Président</li>
                                    <li>par la radiation prononcée pour non-paiement de la cotisation ou pour motifs graves par le Comité Directeur, le membre intéressé ayant été préalablement appelé à fournir des explications</li>
                                    </ul>

                                    <h4>Article 5</h4>
                                    <p>L’association est affiliée à la FFA (Fédération Française d’Aviron) par l’intermédiaire de la ligue régionale des pays de la Loire d’aviron. Elle s’engage à se conformer entièrement aux règles et règlements établis par la FFSA. Elle s’engage à se soumettre aux sanctions disciplinaires qui lui seraient infligées par l’application des dits règlements.</p>
                                    <p>L’association est affiliée à la FFSU (Fédération Française du Sport Universitaire) par l’intermédiaire du Comité Régional des pays de la Loire. Elle s’engage à se conformer entièrement aux règles et règlements établis par la FFSU. Elle s’engage à se soumettre aux sanctions disciplinaires qui lui seraient infligées par l’application des dits règlements.</p>
                                    <p>L’association s’engage à assurer la liberté d’opinion et à respecter les droits de la défense, et particulier des membres faisant l’objet d’une mesure de radiation ou d’exclusion.</p>
                                    <p>L’association s’engage à garantir le fonctionnement démocratique ainsi que la transparence de sa gestion.</p>
                                    <p>L’association s’interdit toute discrimination illégale, en permettant plus particulièrement l’égal accès des femmes et des hommes aux instances dirigeantes de l’association, et veille au respect des règles déontologiques du sport qui sont définis par le Comité National olympique et Sportif Français (CNOSF)</p>
                                    <p>L’association s’engage à faire respecter les règles d’encadrement, d’hygiène et de sécurité applicables aux disciplines pratiquées par ses membres.</p>

                                    <h3>B/ Administration et fonctionnement</h3>

                                    <h4>Article 6</h4>
                                    <p>L’association est administrée par un Comité Directeur composé de membres de droit et de membres élus lors de l’Assemblé Générale. Le Président fixe l’ordre du jour du Comité Directeur. Les délibérations sont acquises à la majorité relative des membres présents ou représentés. Pour toutes les délibérations, les votes ont lieu à bulletin secret, sur la demande d’au moins un membre du Comité Directeur, ou ouvert après mise aux voix par le Président. Le vote par procuration est admis à raison d’un mandat par personne désignée.</p>
                                    <ul>
                                    <p>Sont membres de droit :</p>
                                    <li>le président de l’Université de Nantes ou son représentant</li>
                                    <li>le directeur du SUAPS de l’Université de Nantes</li>
                                    </ul>
                                    <ul>
                                    <p>Peut être élu :</p>
                                    <li>tout licencié depuis plus de 3 mois à jour de sa cotisation.</li>
                                    </ul>

                                    <h4>Article 7</h4>
                                    <p>L’Assemblé Générale se réunit chaque année lors du 4ème trimestre de l’année civile. Elle peut être convoquée extraordinairement, soit par le Comité Directeur, soit à la demande du quart de ses membres actifs. La date de l’Assemblée Générale est publiée au moins quinze jours avant sa réunion.</p>
                                    <p>L’ordre du jour est fixé par le Comité Directeur. L’Assemblée Générale délibère sur les questions mises à l’ordre du jour notamment sur les rapports relatifs à la gestion et à l’activité du Comité Directeur, à la situation morale et financière de l’association.</p>
                                    <p>Elle approuve les comptes de l’exercice clos après rapport du Commissaire aux comptes et vote le budget.
                                    Elle donne quitus aux administrateurs et procède aux élections du Comité Directeur et du commissaire aux comptes qui ne peut être membre du dit Comité Directeur.</p>
                                    <p>Lors de l’Assemblée Générale, les membres étudiants élisent leur Président qui devient par statut Vice Président de l’association.</p>

                                    <h4>Article 8</h4>
                                    <p>Le Comité Directeur, organe exécutif de l’association administre l’association pour une année. Il est composé de 24 membres dont au minimum douze étudiants. Il est renouvelable tous les ans.</p>
                                    <ul>
                                    <p>Le Comité Directeur élit un bureau qui comprend :</p>
                                    <li>un Président obligatoirement personnel de l’Université de Nantes</li>
                                    <li>deux Vice-Présidents dont le Président des étudiants</li>
                                    <li>un Trésorier et Trésorier adjoint</li>
                                    <li>un Secrétaire et Secrétaire adjoint</li>
                                    <li>deux membres</li>
                                    </ul>

                                    <h4>Article 9</h4>
                                    <p>Le Comité Directeur se réunit en séance ordinaire au moins une fois par trimestre sur convocation du bureau : le Président en fixe l’ordre du jour. Le Comité Directeur se réunit en séance extraordinaire à la demande du quart de ses membres. En cas d’égalité de vote la voix du Président est prépondérante. Le Bureau se réunit une fois par mois en dehors des réunions du  Comité Directeur sur convocation du Président.</p>

                                    <h4>Article 10</h4>
                                    <p>Le Comité Directeur est investi par l’Assemblée Générale des pouvoirs les plus étendus pour l’organisation, la gestion et l’administration de l’association.</p>

                                    <h4>Article 11</h4>
                                    <p>Le Comité Directeur applique les décisions de l’Assemblée Générale et agrée les taux des cotisations. Il ordonne les dépenses avec délégation de pouvoir au Trésorier Général. Il décerne les titres de membres d’honneur et de membres bienfaiteurs.</p>

                                    <h4>Article 12</h4>
                                    <p>Il est tenu procès-verbal de toutes les séances et Assemblées de l’association par le Secrétaire. Les procès-verbaux sont signés et transcrits sur un livre coté et paraphé tenu à cet effet au secrétariat de l’association.</p>

                                    <h3>C/ Ressources</h3>

                                    <h4>Article 13</h4>
                                    <ul>
                                    <p>Les ressources de l’association se composent :</p>
                                    <li>des cotisations de ses membres dont le montant est ratifié par l’Assemblée Générale</li>
                                    <li>de subventions qui peuvent lui être accordées</li>
                                    <li>des intérêts et revenus de biens et valeurs qu’elle pourrait posséder et de toutes autres ressources légalement reconnues et compatibles avec sa capacité civile</li>
                                    <li>de dons manuels</li>
                                    Ces ressources servent uniquement à pouvoir au bon fonctionnement de l’ensemble de ses activités.
                                    </ul>

                                    <h4>Article 14</h4>
                                    <p>Il est tenu à jour une comptabilité des recettes et des dépenses avec les journaux annexes. Le budget annuel de la section et le bilan de l’exercice sont arrêtés au 30 septembre de chaque année.</p>

                                    <h3>D/ Modification & dissolution</h3>

                                    <h4>Article 15</h4>
                                    <p>Seule l’Assemblée Générale extraordinaire peut prononcer des modifications de ces statuts ou la dissolution de l’association. Elle doit être composée de la moitié au moins des membres ayant droit d’en faire partie et ses délibérations doivent être prises à la majorité des 2/3 des membres présents ou représentés à raison d’un mandat par personne
                                    Si l’Assemblée Générale extraordinaire n’a pu recueillir le nombre suffisant de membres sur une première convocation, il est convoqué une seconde Assemblée Générale extraordinaire qui délibère valablement, sans condition de quorum, à la majorité simple.</p>

                                    <h4>Article 16</h4>
                                    <p>En cas de dissolution de l’association, son patrimoine devient automatiquement propriété de l’Université de Nantes.</p>",
                'meta_title' => 'reglement-interieur',
                'meta_description' => '',
                'meta_keywords' => 'club, université, nantes, aviron, statuts, officiels'
            ],
            [
                'key' => 'reglement-interieur',
                'title' => '<i class="fa fa-gavel"></i> Le règlement intérieur du club Université Nantes Aviron (UNA)',
                'image' => '',
                'content' => "<p class='quote'>La lecture du règlement intérieur est obligatoire pour tous les membres de l'association Université Nantes Aviron. Tout non-respect pourra entraîner des sanctions allant jusqu'à l'exclusion du club</p>

                                    <h3>Article 1 - Objet</h3>
                                    <p>Les dispositions du présent règlement constituent le règlement intérieur général applicable à toute personne pénétrant dans le club Université Nantes Aviron.</p>
                                    <p>Les consignes que vous trouverez dans ce règlement intérieur sont destinées à améliorer la sécurité et le fonctionnement du club d'aviron.</p>
                                    <p>Dans un intérêt commun, nous vous demandons de les respecter scrupuleusement afin que les activités puissent se dérouler dans les meilleures conditions.</p>
                                    <p>Les usagers spécifiques, tels que les associations sportives ou établissement scolaires, devront outre le règlement intérieur, se conforter également aux conventions particulières les concernant.</p>
                                    <p>Les activités du club d'aviron sont de caractères éducatif et sportif. L'enseignement progressif des activités nautiques implique le suivi des programmes d'activité.</p>
                                    <p>Les tâches courantes de rangement et de nettoyage du matériel font partir des activités et nul ne peut en être dispensé.</p>

                                    <h3>Article 2 - Horaires d'ouverture</h3>
                                    <p>Les jours et horaires d'ouvertures sont fixés en début d'année scolaire par le Bureau Directeur et affichés à l'entré de l'établissement.</p>
                                    <p>Le club d'aviron pourra être fermé exceptionnellement pour les motifs suivants : travaux, hygiène et sécurité, jours fériés, manifestation exceptionnelle, formation du personnel.</p>
                                    <p>Lorsqu'elles sont prévisibles, les dates de fermeture exceptionnelles sont affichées à l'avance, à l'entrée du club d'aviron et sur son site Internet. Lorsqu'elles ne peuvent pas être programmées à l'avance, elles sont annoncées, dès que possible, par voie de presse et d'affichage à l'entrée de l'établissement. Le Bureau Directeur, ainsi que le responsable sportif du club d'aviron ou son représentant, peuvent coordonner la fermeture de l'établissement sans préavis pour tout motif rendant cette fermeture impérative.</p>

                                    <h3>Article 3 - Conditions d'inscription</h3>
                                    <p>Pour accéder aux activités proposées par le club d'aviron, toute personne devra s'être préalablement inscrite auprès des services administratifs.</p>
                                    <p>L'inscription est formalisée par le participant ou son représentant légal par la signature de la fiche d'inscription et le règlement de la cotisation.</p>

                                    <h3>Dossiers administratifs :</h3>
                                    <p>Le participant ou son son représentant légal doit présenter un certificat médical l'autorisant à la pratique de l'aviron et une attestation d'aptitude à la natation.</p>
                                    <p>Les conditions de paiement ainsi que les tarifs sont fixés chaque année par le Bureau Directeur. Ces informations sont affichés à l'entrée de l'établissement.</p>
                                    <p>Les membres d'un groupe constitué (scolaires, associations sportives, ...) doivent satisfaire aux même condition que celles ci-dessus, attestées par le responsable du groupe lors de la signature de la convention d'utilisation du club d'aviron.</p>

                                    <h3>Article 4 - Sécurité</h3>
                                    <p>La décision d'annulation pour raison climatique (température, vent, précipitations, ...) ou pour toutes autres raisons d'hygiène ou de sécurité, pourra être ordonnée par le responsable sportif d'aviron ou son représentant.</p>
                                    <ul><p>Il est strictement interdit :</p>
                                    <li>d'embarquer sans l'autorisation du personnel d'encadrement</li>
                                    <li>de s'éloigner des zones d'évolution prescrites</li>
                                    <li>de stationner dans les chenaux balisés réservés aux embarcations d'un autre type que celles sur lesquelles on évolue</li>
                                    <li>de se baigner volontairement, de provoquer un chavirage</li>
                                    <li>de fumer dans les locaux et sur les bateaux</li>
                                    <li>d'être pieds nus dans le périmètre du club d'aviron</li>
                                    <li>de provoquer volontairement une collision avec une autre embarcation</li>
                                    </ul>

                                    <h3>Article 5 - Matériel</h3>
                                    <p>Le matériel confié aux usagers est conforme à la législation en vigueur et est en bon état de fonctionnement et être utilisé dans le cadre de son utilisation normale.</p>
                                    <p>Durant les activités, il est placé sous la responsabilité de l'usager.</p>
                                    <p>Il doit être manipulé avec soins, inspecté et rangé à chaque fin de séance.</p>
                                    <p>Toutes les avaries constatées doivent être signalées au personnel d'encadrement ou de maintenance du club d'aviron.</p>

                                    <h3>Article 6 - Responsabilités</h3>
                                    <p>Tous les effets personnels doivent être déposés dans les vestiaires prévus à cet usage.</p>
                                    <p>Le club Université Nantes Aviron décline toute responsabilité pour les objets perdus ou volés dans l'établissement.</p>
                                    <p>Les objets trouvés sont à déposer à l'accueil à remettre au personnel de l'UNA.</p>

                                    <h3>Article 7 - Sens civique</h3>
                                    <p>Afin que les activités du club Université Nantes Aviron puissent se dérouler en harmonie avec celles des autres utilisateurs du plan d'eau, les ,usagers doivent un comportement décent et emprunt de courtoisie lors des séances de navigation ou à terre.</p>
                                    <p>Les visiteurs ou usagers ayant une attitude incorrecte ou offensant les moeurs ou incompatible avec le bon fonctionnement du club université Nantes Aviron seront immédiatement invités à quitter l'établissement par le personnel ou la force publique, sans remboursement, et ils pourront, à l'avenir, se voir interdire l'entrée de l'établissement.</p>

                                    <h3>Article 8 - Matériel roulant</h3>
                                    <p>Les véhicules sont réservés aux déplacements du club, et conduit par un membre licencié du club. Tous les documents relatif aux véhicules doivent remplis correctement.</p>
                                    <p>Seul le Comité Directeur de l'UNA pourra répondre aux demandes de prêts pour des utilisateurs non licenciés du club.</p>

                                    <h3>Article 9 - Dispositions antérieures</h3>
                                    <p>Toutes les dispositions antérieures, contraires au présent règlement sont abrogées.</p>",
                'meta_title' => 'Règlement intérieur',
                'meta_description' => '',
                'meta_keywords' => 'club, université, nantes, aviron, reglement, interieur'
            ],
            [
                'key' => 'calendrier',
                'title' => '<i class="fa fa-calendar"></i> Le calendrier du club Université Nantes Aviron (UNA)',
                'image' => '',
                'content' => '<div class="embed-responsive embed-responsive-4by3">
                                <iframe src="https://www.google.com/calendar/embed?src=communication%40una-club.fr&ctz=Europe/Paris" frameborder="0" scrolling="no"></iframe>
                              </div>',
                'meta_title' => 'Calendrier',
                'meta_description' => '',
                'meta_keywords' => 'club, université, nantes, aviron, calendrier, agenda'
            ]
        ]);
    }
}
