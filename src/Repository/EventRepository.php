<?php

namespace App\Repository;

use App\Entity\Event;

use App\Entity\User;
use App\Form\Filter\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function save(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllEventsUpdate(){

        /* Requête en QueryBuilder => récupération de la liste des sorties
        Optimisation du nombre de requêtes */
        $qb = $this->createQueryBuilder('e');
        $qb
            //Jointure avec table state
            ->leftJoin('e.state', 'state')
            ->addSelect('state')

            //Jointure avec table place
            ->leftJoin('e.place', 'place')
            ->addSelect('place')

            //Jointure avec table user pour obtenir l'orgainsateur
            ->leftJoin('e.planner', 'planner')
            ->addSelect('planner')

            //Jointure avec table campus
            ->leftJoin('e.campus', 'campus')
            ->addSelect('campus')

            //Jointure avec table user
            ->leftJoin('e.user', 'user')
            ->addSelect('user');

        //Renvoie une instance de Query
        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * @throws \Exception
     */
    public function findAllEventsFilter(Filter $filter, User $user){

        /* Requête en QueryBuilder => récupération de la liste des sorties
        Optimisation du nombre de requêtes */
        $qb = $this->createQueryBuilder('e');
        $qb
            //Jointure avec table state
            ->leftJoin('e.state', 'state')
            ->andWhere('state.label != :archived')
            ->setParameter('archived', 'archived')
            ->addSelect('state')

            //Jointure avec table place
            ->leftJoin('e.place', 'place')
            ->addSelect('place')

            //Jointure avec table user pour obtenir l'orgainsateur
            ->leftJoin('e.planner', 'planner')
            ->addSelect('planner')

            //Jointure avec table campus
            ->leftJoin('e.campus', 'campus')
            ->addSelect('campus')

            //Jointure avec table user
            ->leftJoin('e.user', 'user')
            ->addSelect('user');

        //Filtre sur le campus sélectionné par l'utilisateur
        if($filter->getCampus()){
            $qb->andWhere('e.campus = :campusName')
                ->setParameter('campusName', $filter->getCampus());
        }

        //Filtre sur le nom saisi par l'utilisateur
        if ($filter->getName()){
            $qb->andWhere('e.name LIKE :name')
                ->setParameter('name', '%' . $filter->getName() . '%');
        }

        //Filtre sur la fourchette de dates d'événements
        if($filter->getStartDate() && $filter->getEndDate()){
            //Prise en compte des deux dates saisies dans le filtre
            $from = new \DateTime($filter->getStartDate()->format(('d-m-Y')));
            $to = new \DateTime($filter->getEndDate()->format(('d-m-Y')));

            //Ajout de la contrainte d'après la double saisie utilisateur
            $qb->andWhere('e.startDateTime BETWEEN :from AND :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }

        //Filtre sur les sorties passées
        if($filter->getEventsPassed()){
            $today = new \DateTime();
            $qb->andWhere('e.startDateTime < :today')
                ->setParameter('today', $today);
        }



//        if($filter->getEventsRegistered()){
//            $qb->andWhere('e.user.')
//
//        }







        //Renvoie une instance de Query
        $query = $qb->getQuery();

        return $query->getResult();
    }


//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
