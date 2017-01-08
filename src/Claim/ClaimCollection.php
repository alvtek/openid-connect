<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\Claim;

use Alvtek\OpenIdConnect\Claim;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;
use Alvtek\OpenIdConnect\Claim\Exception\UndefinedClaimException;
use Alvtek\OpenIdConnect\Claim\Exception\AmbiguousClaimException;
use Alvtek\OpenIdConnect\SerialisableInterface;
use Iterator;
use Countable;

/**
 * Collection of claims with some helpful functionality and comparison functions
 */
final class ClaimCollection implements Iterator, Countable, SerialisableInterface
{
    /** @var Claim[] */
    private $claims;

    /** @var integer */
    private $position;

    private function __construct(array $claims)
    {
        $this->position = 0;
        $this->claims = [];
        
        foreach ($claims as $claim) {
            if (!$claim instanceof Claim) {
                throw new InvalidArgumentException(sprintf(
                    "expecting instance of %s got instance of %s", 
                    Claim::class, 
                    get_class($claim)
                ));
            }
            
            if ($this->hasClaim($claim)) {
                continue;
            }
            
            $this->claims[] = $claim;
        }
    }

    /**
     * @param array $data
     * @return ClaimCollection
     */
    public static function fromApiData(array $data) : ClaimCollection
    {
        $claims = [];
        
        foreach ($data as $claimData) {
            if (!array_key_exists('type', $claimData)) {
                throw new InvalidArgumentException("expecting 'type' array key "
                    . "for claim");
            }
            
            if (!array_key_exists('value', $claimData)) {
                throw new InvalidArgumentException("expecting 'value' array "
                    . "key to be set");
            }
            
            $claims[] = new Claim($claimData['type'], $claimData['value']);
        }

        return static::fromArray($claims);
    }

    /**
     * @param array $data
     * @return ClaimCollection
     */
    public static function fromArrayOfStrings(array $data) : ClaimCollection
    {
        Assert::that($data)->isArray();

        $claims = [];

        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $claims[] = new Claim($key, $value);
                continue;
            }
            
            if (is_array($value)) {
                foreach ($value as $item) {
                    $claims[] = new Claim($key, $item);
                }
            }
        }

        return new static($claims);
    }

    /**
     * @return array
     */
    public function serialise() : array
    {
        $output = [];
        
        foreach ($this->claims as $claim) {
            $output[] = $claim->serialise();
        }

        return $output;
    }

    /**
     * 
     * @return array
     */
    public function toArray() : array
    {
        $output = [];

        foreach ($this->claims as $claim) {
            if (isset($output[$claim->type()])) {
                $claimArray = is_array($output[$claim->type()]) ? $output[$claim->type()] : [$output[$claim->type()]];
                $claimArray[] = $claim->value();
                $output[$claim->type()] = $claimArray;
                continue;
            }
            $output[$claim->type()] = $claim->value();
        }

        return $output;
    }

    /**
     * @return boolean
     */
    public function isEmpty() : bool
    {
        return empty($this->claims);
    }

    /**
     * 
     * @param string $type
     * @return Claim
     * @throws UndefinedClaimException
     * @throws AmbiguousClaimException
     */
    public function getUniqueClaimByType($type) : Claim
    {
        $claims = $this->getClaimsByType($type);
        if ($claims->isEmpty()) {
            throw new UndefinedClaimException(sprintf("The claim of type '%s' "
                . "is not present within the collection.", $type));
        }
        
        if (count($claims) > 1) {
            throw new AmbiguousClaimException(sprintf("The claim of type '%s' "
                . "is ambiguous, multiple claims of this type exist.", $type));
        }
        
        return $claims->current();
    }

    /**
     * @param type $type
     * @return boolean
     */
    public function hasClaimType($type) : bool
    {
        foreach ($this->claims as $claim) {
            if ($claim->type() === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $type
     * @return ClaimCollection
     */
    public function getClaimsByType($type) : ClaimCollection
    {
        $claims = [];
        
        foreach ($this->claims as $claim) {
            if ($claim->type() === $type) {
                $claims[] = $claim;
            }
        }

        return new static($claims);
    }

    /**
     * @param Claim $claim
     * @return boolean
     */
    public function hasClaim(Claim $claim) : bool
    {
        foreach ($this->claims as $existingClaim) {
            if (
                $existingClaim->type() === $claim->type() && 
                $existingClaim->value() === $claim->value()
            ) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * @param Claim $claim
     * @return ClaimCollection
     */
    public function withClaim(Claim $claim) : ClaimCollection
    {
        $claims = $this->claims;
        $claims[] = $claim;
        
        return new static($claims);
    }

    /**
     * @param \Alvtek\OpenIdConnect\Claim\Collection $claims
     * @return ClaimCollection
     */
    public function merge(ClaimCollection $claims) : ClaimCollection
    {
        $mergedClaims = $claims->claims;
        
        foreach ($this->claims as $claim) {
            $mergedClaims[] = $claim;
        }
        
        return new static($mergedClaims);
    }

    /**
     * @param \Alvtek\OpenIdConnect\Claim\Collection $claims
     * @return boolean
     */
    public function equals(ClaimCollection $claims) : bool
    {
        if (count($this) !== count($claims)) {
            return false;
        }
        
        foreach ($this->claims as $existingClaim) {
            if (!$claims->hasClaim($existingClaim)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * 
     * @return integer
     */
    public function count() : int
    {
        return count($this->claims);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /** @return Claim */
    public function current() : Claim
    {
        return $this->claims[$this->position];
    }

    /**
     * @return integer
     */
    public function key() : int
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    /**
     * @return boolean
     */
    public function valid() : bool
    {
        return isset($this->claims[$this->position]);
    }
}
