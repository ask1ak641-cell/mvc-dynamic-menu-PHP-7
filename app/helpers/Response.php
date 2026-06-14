<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  Response Helper - JSON response yang konsisten
 * ═══════════════════════════════════════════════════════════════
 */
class Response
{
    /**
     * Response sukses
     */
    public static function success($message, $data = null, $code = 200)
    {
        $response = [
            'status'  => 'success',
            'message' => $message,
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }

        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Response error
     */
    public static function error($message, $code = 400, $errors = null)
    {
        $response = [
            'status'  => 'error',
            'message' => $message,
        ];
        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Response untuk data paginated
     */
    public static function paginated(array $paginateResult): void
    {
        self::success('Data berhasil diambil', [
            'items'       => $paginateResult['data'],
            'total'       => $paginateResult['total'],
            'page'        => $paginateResult['page'],
            'per_page'    => $paginateResult['per_page'],
            'total_pages' => $paginateResult['total_pages'],
        ]);
    }
}
