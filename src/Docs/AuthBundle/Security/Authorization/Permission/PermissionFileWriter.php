<?php
namespace Docs\AuthBundle\Security\Authorization\Permission;

/**
 * Writes the permissions into a file
 * @author h.botev
 *
 */
class PermissionFileWriter
{
    /**
     * cache dir
     * @var string
     */
    protected $cacheDir;

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * Return the filename for the cache of the role
     * permissions. If the file doesn't exist, attempt to create it
     * @param string $roleName
     * @throws \RuntimeException
     * @return string
     */
    public function getCacheFileName($roleName)
    {
        $cacheDir = $this->cacheDir . "/security";

        if (!is_dir($cacheDir)) {
            if (!@mkdir($cacheDir)) {
                throw new \RuntimeException("Couldn't create cache dir {$cacheDir}");
            }
        }

        $file = $cacheDir . "/primissions_" . $roleName . "_cache.php";

        if (!is_file($file)) {
            if (!@touch($file)) {
                throw new \RuntimeException("Couldn't create cache file {$file}");
            }
            // init file contents
            $f = fopen($file, "w+");
            fwrite($f, "<?php\n\treturn [];");
            fclose($f);
        }

        return $file;
    }

    /**
     * Write permissions to a file
     * @param array $permissions
     * @param int $roleID
     */
    public function write(array $permissions, $roleName)
    {
        $filePath = $this->getCacheFileName($roleName);

        $f = fopen($filePath, "w+");

        fwrite($f, "<?php\n");
        fwrite($f, "use Docs\AuthBundle\Security\Authorization\Permission\Permission;\n");
        fwrite($f, "return array(\n");

        foreach ($permissions as $permission) {
            $id = $permission->getId();
            $access = $permission->getAccess() ? : 0;

            $el = "\t'{$id}' => new Permission('{$id}',{$access}),\n";

            fwrite($f, $el);
        }

        fwrite($f, ");");

        fclose($f);
    }
}
