<?php
namespace app\controllers;

use app\models\Products;
use app\models\Users;
use Yii;
use yii\rest\Controller;

class ProductsController extends Controller
{
    public $modelClass = 'app\models\Products';
    public function actionProducts()
    {
        $products = Products::find()->all();
        if ($products !== null) {
            $response = $this->response;
            $response->statusCode = 201;
            $response->data = $products;
        } else {
            $response = $this->response;
            $response->statusCode = 404;
            $response->data = [
                'error' => [
                    'code' => 404,
                    'message' => 'No products found in the system',
                ],
            ];
            return $response;
        }
    }

    public function actionProduct($id_product)
    {
        $product = Products::findOne($id_product);
        if ($product !== null) {
            $response = $this->response;
            $response->statusCode = 200;
            $response->data = [
                $product,
            ];
        } else {
            $response = $this->response;
            $response->statusCode = 404;
            $response->data = [
                'error' => [
                    'code' => 404,
                    'message' => 'Product not found',
                ],
            ];
            return $response;
        }
    }
    public function actionAdd()
    {
        $data = Yii::$app->request->getBodyParams();
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));

        if ($user !== null && $user->admin !== 0) {
            $product = new Products();
            $product->load($data, ''); // Загружаем данные в модель

            if ($product->validate()) { // Проверяем валидацию
                if ($product->save()) {
                    $response = $this->response;
                    $response->statusCode = 200;
                    $response->data = [$product];
                    return $response;
                } else {
                    // Обработка ошибок при сохранении игры
                    $response = $this->response;
                    $response->statusCode = 500;
                    $response->data = [
                        'error' => [
                            'code' => 500,
                            'message' => 'Ошибка сохранения товара',
                            'errors' => $product->getErrors(),
                        ],
                    ];
                    return $response;
                }
            } else {
                // Ошибка валидации данных
                $response = $this->response;
                $response->statusCode = 422;
                $response->data = [
                    'error' => [
                        'code' => 422,
                        'message' => 'Ошибка валидации данных для создания товара',
                        'errors' => $product->getErrors(),
                    ],
                ];
                return $response;
            }
        } else {
            // Ошибка отсутствия прав администратора
            $response = $this->response;
            $response->statusCode = 401;
            $response->data = [
                'error' => [
                    'code' => 401,
                    'message' => 'Отсутствуют права администратора',
                ],
            ];
            return $response;
        }
    }

    public function actionDelete($id_product)
    {
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));
        if ($user !== null && $user->admin !== 0) {
            $product = Products::findOne($id_product);
            if ($product !== null) {
                $product->delete();
                $response = $this->response;
                $response->statusCode = 201;
                $response->data = [
                    'error' => [
                        'code' => 201,
                        'message' => 'Товар успешно удален!',
                    ],
                ];
                return $response;
            } else {
                $response = $this->response;
                $response->statusCode = 404;
                $response->data = [
                    'error' => [
                        'code' => 404,
                        'message' => 'product not found',
                    ],
                ];
                return $response;
            }
        } else {
            $response = $this->response;
            $response->statusCode = 401;
            $response->data = [
                'error' => [
                    'code' => 401,
                    'message' => 'Отсутствуют права администратора',
                ],
            ];
        }
    }

    public function actionUpdate($id_product)
    {
        $user = $this->findUserByToken(str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization')));
        if ($user !== null && $user->admin !== 0) {
            $product = Products::findOne($id_product);
            if ($product !== null) {
                ;
                $product->load(Yii::$app->request->post(), '');
                $product->save();

                $response = $this->response;
                $response->statusCode = 404;
                $response->data = [
                    'error' => [
                        'code' => 201,
                        'message' => 'Товар успешно изменен!',
                    ],
                ];
                return $response;
            } else {
                $response = $this->response;
                $response->statusCode = 404;
                $response->data = [
                    'error' => [
                        'code' => 404,
                        'message' => 'Product not found',
                    ],
                ];
                return $response;
            }
        } else {
            $response = $this->response;
            $response->statusCode = 401;
            $response->data = [
                'error' => [
                    'code' => 401,
                    'message' => 'Отсутствуют права администратора',
                ],
            ];
        }
    }

    private function findUserByToken($token)
    {
        return Users::findOne(['token' => $token]);
    }
}