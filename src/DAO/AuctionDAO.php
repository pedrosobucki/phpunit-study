<?php

namespace PSobucki\Auction\DAO;

use DateTimeImmutable;
use Exception;
use PDO;
use PSobucki\Auction\Infra\ConnectionCreator;
use PSobucki\Auction\Model\Auction;

class AuctionDAO
{
    private PDO $con;

    private const INSERT = '
        INSERT INTO leiloes (
            descricao,
            finalizado,
            dataInicio
        ) 
        VALUES (?, ?, ?)
    ';

    private const FETCH_BASED_ON_CLOSED_STATUS = '
        SELECT * 
        FROM leiloes 
        WHERE finalizado = ?
    ';

    private const UPDATE = '
        UPDATE leiloes 
        SET descricao = :descricao,
            dataInicio = :dataInicio, 
            finalizado = :finalizado 
        WHERE id = :id
    ';

    public function __construct()
    {
        $this->con = ConnectionCreator::getConnection();
    }

    public function save(Auction $auction): bool
    {
        $stm = $this->con->prepare(self::INSERT);
        $stm->bindValue(1, $auction->description(), PDO::PARAM_STR);
        $stm->bindValue(2, $auction->isClosed(), PDO::PARAM_BOOL);
        $stm->bindValue(3, $auction->startDate()->format('Y-m-d'));

        return $stm->execute();
    }

    /**
     * @return Auction[]
     * @throws Exception
     */
    public function fetchNotClosed(): array
    {
        return $this->fetchBasedOnClosedStatus(false);
    }

    /**
     * @return Auction[]
     * @throws Exception
     */
    public function fetchClosed(): array
    {
        return $this->fetchBasedOnClosedStatus(true);
    }

    /**
     * @return Auction[]
     * @throws Exception
     */
    private function fetchBasedOnClosedStatus(bool $closed): array
    {
        $stm = $this->con->prepare(self::FETCH_BASED_ON_CLOSED_STATUS);
        $stm->bindValue(1, $closed, \PDO::PARAM_BOOL);
        $stm->setFetchMode(PDO::FETCH_ASSOC);

        $stm->execute();
        $auctions = [];

        while($fetch = $stm->fetch(PDO::FETCH_ASSOC)) {
            $auction = new Auction($fetch['descricao'], new DateTimeImmutable($fetch['dataInicio']), $fetch['id']);

            if ($fetch['finalizado']) {
                $auction->close();
            }
            $auctions[] = $auction;
        }

        return $auctions;
    }

    public function update(Auction $auction): bool
    {
        $stm = $this->con->prepare(self::UPDATE);

        $stm->bindValue(':descricao', $auction->description());
        $stm->bindValue(':dataInicio', $auction->startDate()->format('Y-m-d'));
        $stm->bindValue(':finalizado', $auction->isClosed(), PDO::PARAM_BOOL);
        $stm->bindValue(':id', $auction->id(), PDO::PARAM_INT);

        return $stm->execute();
    }
}